<x-app-sidebar-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Invoice') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="invoiceForm()">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('invoices.store') }}" method="POST" id="invoice-form">
                        @csrf

                        <!-- Invoice Header -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Invoice Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Searchable Patient Dropdown -->
                                <div class="relative">
                                    <label for="patient_search" class="block text-sm font-medium text-gray-700">Patient *</label>
                                    <div class="relative mt-1">
                                        <input
                                            type="text"
                                            x-model="patientSearch"
                                            @click="patientDropdownOpen = true"
                                            @input="patientDropdownOpen = true"
                                            placeholder="Search patient by name..."
                                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                        >
                                        <input type="hidden" name="patient_id" x-model="selectedPatient" required>

                                        <!-- Dropdown List -->
                                        <div
                                            x-show="patientDropdownOpen && filteredPatients.length > 0"
                                            @click.away="patientDropdownOpen = false"
                                            class="absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-auto"
                                        >
                                            <template x-for="patient in filteredPatients" :key="patient.id">
                                                <div
                                                    @click="selectPatient(patient)"
                                                    class="px-3 py-2 cursor-pointer hover:bg-indigo-50 border-b border-gray-100"
                                                >
                                                    <div class="font-medium text-gray-900" x-text="patient.full_name"></div>
                                                    <div class="text-xs text-gray-500" x-text="patient.email"></div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                    @error('patient_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Dynamic Appointment Dropdown -->
                                <div>
                                    <label for="appointment_id" class="block text-sm font-medium text-gray-700">Related Appointment (Optional)</label>
                                    <select
                                        id="appointment_id"
                                        name="appointment_id"
                                        x-model="selectedAppointment"
                                        :disabled="!selectedPatient"
                                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 disabled:bg-gray-100"
                                    >
                                        <option value="">Select Appointment</option>
                                        <template x-for="appointment in patientAppointments" :key="appointment.id">
                                            <option :value="appointment.id" x-text="appointment.display"></option>
                                        </template>
                                    </select>
                                    @error('appointment_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="invoice_date" class="block text-sm font-medium text-gray-700">Invoice Date</label>
                                    <input type="date" id="invoice_date" name="invoice_date" value="{{ old('invoice_date', date('Y-m-d')) }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    @error('invoice_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="due_date" class="block text-sm font-medium text-gray-700">Due Date</label>
                                    <input type="date" id="due_date" name="due_date" value="{{ old('due_date', date('Y-m-d', strtotime('+30 days'))) }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    @error('due_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                    <select id="status" name="status" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="pending" {{ old('status', 'pending') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="paid" {{ old('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                        <option value="overdue" {{ old('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                                        <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                    @error('status')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="payment_method" class="block text-sm font-medium text-gray-700">Payment Method</label>
                                    <select id="payment_method" name="payment_method" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="">Select Payment Method</option>
                                        <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                        <option value="credit_card" {{ old('payment_method') == 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                                        <option value="debit_card" {{ old('payment_method') == 'debit_card' ? 'selected' : '' }}>Debit Card</option>
                                        <option value="check" {{ old('payment_method') == 'check' ? 'selected' : '' }}>Check</option>
                                        <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                        <option value="insurance" {{ old('payment_method') == 'insurance' ? 'selected' : '' }}>Insurance</option>
                                    </select>
                                    @error('payment_method')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Invoice Items -->
                        <div class="mb-8">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold text-gray-900">Invoice Items</h3>
                                <button type="button" id="add-item" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm font-medium">
                                    + Add Item
                                </button>
                            </div>
                            
                            <div id="invoice-items" class="space-y-4">
                                <!-- Initial item row -->
                                <div class="invoice-item bg-gray-50 p-4 rounded-lg">
                                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                                        <div class="md:col-span-2">
                                            <label class="block text-sm font-medium text-gray-700">Description</label>
                                            <input type="text" name="items[0][description]" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Treatment or service description">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Quantity</label>
                                            <input type="number" name="items[0][quantity]" value="1" min="1" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 quantity-input">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Unit Price ($)</label>
                                            <input type="number" name="items[0][unit_price]" step="0.01" min="0" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 unit-price-input">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Total</label>
                                            <div class="mt-1 px-3 py-2 bg-gray-100 border border-gray-300 rounded-md text-gray-700 item-total">$0.00</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Totals -->
                            <div class="mt-6 border-t pt-4">
                                <div class="flex justify-end">
                                    <div class="w-72">
                                        <div class="flex justify-between items-center mb-2">
                                            <span class="text-sm font-medium text-gray-700">Subtotal:</span>
                                            <span id="subtotal" class="text-sm text-gray-900">$0.00</span>
                                        </div>
                                        <div class="flex justify-between items-center mb-2">
                                            <span class="text-sm font-medium text-gray-700">Tax (%):</span>
                                            <div class="flex items-center space-x-2">
                                                <input type="number" id="tax_rate" name="tax_rate" value="{{ old('tax_rate', '0') }}" step="0.01" min="0" max="100" class="w-20 px-2 py-1 border border-gray-300 rounded text-sm">
                                                <span id="tax-amount" class="text-sm text-gray-900">$0.00</span>
                                            </div>
                                        </div>
                                        <div class="flex justify-between items-center mb-4 text-lg font-semibold border-t pt-2">
                                            <span class="text-gray-900">Total:</span>
                                            <span id="total" class="text-indigo-600">$0.00</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="mb-8">
                            <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                            <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Additional notes or payment terms...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Hidden calculated fields -->
                        <input type="hidden" id="subtotal_input" name="subtotal" value="0">
                        <input type="hidden" id="tax_amount_input" name="tax_amount" value="0">
                        <input type="hidden" id="total_amount_input" name="total_amount" value="0">

                        <!-- Submit Buttons -->
                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('invoices.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                Create Invoice
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Alpine.js component for invoice form
        function invoiceForm() {
            return {
                patients: @json($patients),
                allAppointments: @json($appointments),
                patientSearch: '',
                patientDropdownOpen: false,
                selectedPatient: '{{ old('patient_id', '') }}',
                selectedAppointment: '{{ old('appointment_id', '') }}',

                get filteredPatients() {
                    if (!this.patientSearch) {
                        return this.patients;
                    }
                    const search = this.patientSearch.toLowerCase();
                    return this.patients.filter(patient =>
                        patient.first_name.toLowerCase().includes(search) ||
                        patient.last_name.toLowerCase().includes(search) ||
                        patient.full_name.toLowerCase().includes(search) ||
                        (patient.email && patient.email.toLowerCase().includes(search))
                    );
                },

                get patientAppointments() {
                    if (!this.selectedPatient) {
                        return [];
                    }
                    return this.allAppointments
                        .filter(apt => apt.patient_id == this.selectedPatient)
                        .map(apt => {
                            // Format date
                            const date = new Date(apt.appointment_date);
                            const formattedDate = date.toLocaleDateString('en-US', {
                                month: 'short',
                                day: 'numeric',
                                year: 'numeric'
                            });

                            // Parse start time (in HH:MM format)
                            const [hours, minutes] = apt.appointment_time.split(':');
                            const startTime = new Date();
                            startTime.setHours(parseInt(hours), parseInt(minutes), 0);

                            // Calculate end time
                            const endTime = new Date(startTime);
                            endTime.setMinutes(endTime.getMinutes() + apt.duration);

                            // Format times
                            const timeOptions = { hour: 'numeric', minute: '2-digit', hour12: true };
                            const formattedStart = startTime.toLocaleTimeString('en-US', timeOptions);
                            const formattedEnd = endTime.toLocaleTimeString('en-US', timeOptions);

                            return {
                                id: apt.id,
                                display: `${formattedDate} • ${formattedStart} - ${formattedEnd} (${apt.duration}min)`
                            };
                        });
                },

                selectPatient(patient) {
                    this.selectedPatient = patient.id;
                    this.patientSearch = patient.full_name;
                    this.patientDropdownOpen = false;
                    this.selectedAppointment = '';
                }
            };
        }

        let itemIndex = 1;

        document.addEventListener('DOMContentLoaded', function() {
            // Add item functionality
            document.getElementById('add-item').addEventListener('click', function() {
                const container = document.getElementById('invoice-items');
                const newItem = document.createElement('div');
                newItem.className = 'invoice-item bg-gray-50 p-4 rounded-lg';
                newItem.innerHTML = `
                    <div class="grid grid-cols-1 md:grid-cols-6 gap-4 items-end">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Description</label>
                            <input type="text" name="items[${itemIndex}][description]" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Treatment or service description">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Quantity</label>
                            <input type="number" name="items[${itemIndex}][quantity]" value="1" min="1" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 quantity-input">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Unit Price ($)</label>
                            <input type="number" name="items[${itemIndex}][unit_price]" step="0.01" min="0" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 unit-price-input">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Total</label>
                            <div class="mt-1 px-3 py-2 bg-gray-100 border border-gray-300 rounded-md text-gray-700 item-total">$0.00</div>
                        </div>
                        <div>
                            <button type="button" class="remove-item bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded text-sm">Remove</button>
                        </div>
                    </div>
                `;
                container.appendChild(newItem);
                itemIndex++;
                attachItemEvents(newItem);
                calculateTotals();
            });

            // Remove item functionality
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-item')) {
                    e.target.closest('.invoice-item').remove();
                    calculateTotals();
                }
            });

            // Attach events to existing items
            document.querySelectorAll('.invoice-item').forEach(attachItemEvents);

            // Tax rate change
            document.getElementById('tax_rate').addEventListener('input', calculateTotals);

            calculateTotals();
        });

        function attachItemEvents(item) {
            const quantityInput = item.querySelector('.quantity-input');
            const unitPriceInput = item.querySelector('.unit-price-input');
            
            [quantityInput, unitPriceInput].forEach(input => {
                input.addEventListener('input', function() {
                    calculateItemTotal(item);
                    calculateTotals();
                });
            });
        }

        function calculateItemTotal(item) {
            const quantity = parseFloat(item.querySelector('.quantity-input').value) || 0;
            const unitPrice = parseFloat(item.querySelector('.unit-price-input').value) || 0;
            const total = quantity * unitPrice;
            item.querySelector('.item-total').textContent = '$' + total.toFixed(2);
        }

        function calculateTotals() {
            let subtotal = 0;
            document.querySelectorAll('.invoice-item').forEach(item => {
                const quantity = parseFloat(item.querySelector('.quantity-input').value) || 0;
                const unitPrice = parseFloat(item.querySelector('.unit-price-input').value) || 0;
                subtotal += quantity * unitPrice;
                calculateItemTotal(item);
            });

            const taxRate = parseFloat(document.getElementById('tax_rate').value) || 0;
            const taxAmount = subtotal * (taxRate / 100);
            const total = subtotal + taxAmount;

            document.getElementById('subtotal').textContent = '$' + subtotal.toFixed(2);
            document.getElementById('tax-amount').textContent = '$' + taxAmount.toFixed(2);
            document.getElementById('total').textContent = '$' + total.toFixed(2);

            // Update hidden inputs
            document.getElementById('subtotal_input').value = subtotal.toFixed(2);
            document.getElementById('tax_amount_input').value = taxAmount.toFixed(2);
            document.getElementById('total_amount_input').value = total.toFixed(2);
        }
    </script>
</x-app-sidebar-layout>