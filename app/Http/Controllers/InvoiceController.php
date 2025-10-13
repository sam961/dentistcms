<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Invoice;
use App\Models\Patient;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with(['patient', 'appointment'])
            ->orderBy('invoice_date', 'desc')
            ->paginate(15);

        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        $patients = Patient::orderBy('last_name')->get();
        $appointments = Appointment::with(['patient', 'dentist'])->get();

        return view('invoices.create', compact('patients', 'appointments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'subtotal' => 'required|numeric|min:0',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'tax_amount' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'status' => 'required|in:pending,paid,overdue,cancelled',
            'payment_method' => 'nullable|in:cash,credit_card,debit_card,check,bank_transfer,insurance',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $invoice = Invoice::create($validated);

        // Create invoice items
        foreach ($request->items as $item) {
            $invoice->items()->create([
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
            ]);
        }

        return redirect()->route('invoices.index')->with('success', 'Invoice created successfully.');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['patient', 'appointment', 'items.treatment']);

        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        $patients = Patient::orderBy('last_name')->get();
        $appointments = Appointment::with(['patient', 'dentist'])->get();

        return view('invoices.edit', compact('invoice', 'patients', 'appointments'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'subtotal' => 'required|numeric|min:0',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'discount_amount' => 'nullable|numeric|min:0',
            'status' => 'required|in:draft,sent,paid,partially_paid,overdue,cancelled',
            'payment_method' => 'nullable|in:cash,credit_card,debit_card,insurance,bank_transfer,other',
            'payment_date' => 'nullable|date',
            'paid_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $validated['tax_amount'] = $validated['subtotal'] * ($validated['tax_rate'] / 100);
        $validated['total_amount'] = $validated['subtotal'] + $validated['tax_amount'] - ($validated['discount_amount'] ?? 0);

        $invoice->update($validated);

        return redirect()->route('invoices.index')->with('success', 'Invoice updated successfully.');
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();

        return redirect()->route('invoices.index')->with('success', 'Invoice deleted successfully.');
    }

    public function createForAppointment(Appointment $appointment)
    {
        $appointment->load(['patient', 'treatments']);

        return view('invoices.create-for-appointment', compact('appointment'));
    }

    public function print(Invoice $invoice)
    {
        $invoice->load(['patient', 'appointment', 'items.treatment']);

        return view('invoices.print', compact('invoice'));
    }
}
