<?php

namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Treatment;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class InvoiceSeeder extends Seeder
{
    public function run(): void
    {
        $patients = Patient::all();
        $appointments = Appointment::with('treatments')->get();
        $treatments = Treatment::all();
        
        if ($patients->isEmpty()) {
            return;
        }

        $invoices = [
            [
                'patient_id' => $patients->random()->id,
                'appointment_id' => $appointments->isNotEmpty() ? $appointments->random()->id : null,
                'invoice_date' => Carbon::now()->subDays(15),
                'due_date' => Carbon::now()->subDays(15)->addDays(30),
                'subtotal' => 450.00,
                'tax_rate' => 8.25,
                'tax_amount' => 37.13,
                'discount_amount' => 0.00,
                'total_amount' => 487.13,
                'paid_amount' => 487.13,
                'status' => 'paid',
                'payment_method' => 'credit_card',
                'payment_date' => Carbon::now()->subDays(10),
                'notes' => 'Insurance covered 80% of treatment',
            ],
            [
                'patient_id' => $patients->random()->id,
                'appointment_id' => $appointments->isNotEmpty() ? $appointments->random()->id : null,
                'invoice_date' => Carbon::now()->subDays(8),
                'due_date' => Carbon::now()->subDays(8)->addDays(30),
                'subtotal' => 200.00,
                'tax_rate' => 8.25,
                'tax_amount' => 16.50,
                'discount_amount' => 20.00,
                'total_amount' => 196.50,
                'paid_amount' => 0.00,
                'status' => 'sent',
                'payment_method' => null,
                'payment_date' => null,
                'notes' => 'Senior citizen discount applied',
            ],
            [
                'patient_id' => $patients->random()->id,
                'appointment_id' => $appointments->isNotEmpty() ? $appointments->random()->id : null,
                'invoice_date' => Carbon::now()->subDays(5),
                'due_date' => Carbon::now()->subDays(5)->addDays(30),
                'subtotal' => 800.00,
                'tax_rate' => 8.25,
                'tax_amount' => 66.00,
                'discount_amount' => 0.00,
                'total_amount' => 866.00,
                'paid_amount' => 400.00,
                'status' => 'partially_paid',
                'payment_method' => 'bank_transfer',
                'payment_date' => Carbon::now()->subDays(2),
                'notes' => 'Payment plan arranged - remaining balance due in 30 days',
            ],
            [
                'patient_id' => $patients->random()->id,
                'appointment_id' => $appointments->isNotEmpty() ? $appointments->random()->id : null,
                'invoice_date' => Carbon::now()->subDays(45),
                'due_date' => Carbon::now()->subDays(15),
                'subtotal' => 300.00,
                'tax_rate' => 8.25,
                'tax_amount' => 24.75,
                'discount_amount' => 0.00,
                'total_amount' => 324.75,
                'paid_amount' => 0.00,
                'status' => 'overdue',
                'payment_method' => null,
                'payment_date' => null,
                'notes' => 'Follow-up required - patient has not responded to payment notices',
            ],
            [
                'patient_id' => $patients->random()->id,
                'appointment_id' => null,
                'invoice_date' => Carbon::now(),
                'due_date' => Carbon::now()->addDays(30),
                'subtotal' => 150.00,
                'tax_rate' => 8.25,
                'tax_amount' => 12.38,
                'discount_amount' => 0.00,
                'total_amount' => 162.38,
                'paid_amount' => 0.00,
                'status' => 'draft',
                'payment_method' => null,
                'payment_date' => null,
                'notes' => 'Routine cleaning and examination',
            ],
        ];

        foreach ($invoices as $invoiceData) {
            $invoice = Invoice::create($invoiceData);
            
            // Create invoice items based on the appointment treatments or random treatments
            if ($invoice->appointment && $invoice->appointment->treatments->isNotEmpty()) {
                foreach ($invoice->appointment->treatments as $treatment) {
                    InvoiceItem::create([
                        'invoice_id' => $invoice->id,
                        'treatment_id' => $treatment->id,
                        'description' => $treatment->name,
                        'quantity' => $treatment->pivot->quantity ?? 1,
                        'unit_price' => $treatment->pivot->price ?? $treatment->price,
                        'total_price' => ($treatment->pivot->quantity ?? 1) * ($treatment->pivot->price ?? $treatment->price),
                    ]);
                }
            } else {
                // Create random invoice items for invoices without appointments
                $itemCount = rand(1, 3);
                for ($i = 0; $i < $itemCount; $i++) {
                    $treatment = $treatments->random();
                    $quantity = rand(1, 2);
                    InvoiceItem::create([
                        'invoice_id' => $invoice->id,
                        'treatment_id' => $treatment->id,
                        'description' => $treatment->name,
                        'quantity' => $quantity,
                        'unit_price' => $treatment->price,
                        'total_price' => $quantity * $treatment->price,
                    ]);
                }
            }
        }
    }
}