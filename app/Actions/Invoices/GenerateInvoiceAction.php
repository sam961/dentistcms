<?php

namespace App\Actions\Invoices;

use App\Models\Appointment;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;

class GenerateInvoiceAction
{
    public function fromAppointment(Appointment $appointment): Invoice
    {
        return DB::transaction(function () use ($appointment) {
            // Generate invoice number
            $year = date('Y');
            $lastInvoice = Invoice::whereYear('created_at', $year)
                ->orderBy('id', 'desc')
                ->first();

            if ($lastInvoice && preg_match('/INV-'.$year.'-(\d+)/', $lastInvoice->invoice_number, $matches)) {
                $sequential = intval($matches[1]) + 1;
            } else {
                $sequential = 1;
            }

            $invoiceNumber = sprintf('INV-%s-%06d', $year, $sequential);

            // Create invoice
            $invoice = Invoice::create([
                'patient_id' => $appointment->patient_id,
                'appointment_id' => $appointment->id,
                'invoice_number' => $invoiceNumber,
                'invoice_date' => now(),
                'due_date' => now()->addDays(30),
                'subtotal' => $appointment->treatment->price ?? 0,
                'tax_rate' => 0,
                'tax_amount' => 0,
                'discount' => 0,
                'total_amount' => $appointment->treatment->price ?? 0,
                'paid_amount' => 0,
                'status' => 'sent',
            ]);

            // Create invoice item
            if ($appointment->treatment) {
                $invoice->items()->create([
                    'treatment_id' => $appointment->treatment_id,
                    'description' => $appointment->treatment->name,
                    'quantity' => 1,
                    'unit_price' => $appointment->treatment->price,
                    'total' => $appointment->treatment->price,
                ]);
            }

            return $invoice;
        });
    }
}
