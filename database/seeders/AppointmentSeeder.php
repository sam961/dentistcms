<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Dentist;
use App\Models\Patient;
use App\Models\Treatment;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AppointmentSeeder extends Seeder
{
    public function run(): void
    {
        $patients = Patient::all();
        $dentists = Dentist::all();
        $treatments = Treatment::all();

        if ($patients->isEmpty() || $dentists->isEmpty()) {
            return;
        }

        // Generate appointments for September 2025 (weekdays only)
        $appointments = [];
        $startDate = Carbon::create(2025, 9, 1); // September 1, 2025
        $endDate = Carbon::create(2025, 9, 30); // September 30, 2025

        $appointmentTypes = [
            'Regular Checkup' => ['duration' => 30, 'reason' => 'Routine dental examination and cleaning'],
            'Treatment' => ['duration' => 45, 'reason' => 'Dental filling and restoration'],
            'Consultation' => ['duration' => 60, 'reason' => 'Initial consultation and treatment planning'],
            'Surgery' => ['duration' => 90, 'reason' => 'Oral surgery procedure'],
            'Follow-up' => ['duration' => 30, 'reason' => 'Post-treatment checkup'],
            'Cleaning' => ['duration' => 45, 'reason' => 'Professional teeth cleaning'],
            'Cosmetic' => ['duration' => 75, 'reason' => 'Cosmetic dental procedure'],
            'Emergency' => ['duration' => 60, 'reason' => 'Emergency dental treatment'],
            'Orthodontic' => ['duration' => 90, 'reason' => 'Orthodontic evaluation and treatment'],
            'Root Canal' => ['duration' => 120, 'reason' => 'Root canal therapy'],
        ];

        $timeSlots = ['09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '12:00', '12:30', '13:00', '13:30', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30'];

        $statuses = ['confirmed', 'confirmed', 'confirmed', 'confirmed', 'confirmed']; // All appointments confirmed

        // Loop through each day in September
        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            // Only create appointments on weekdays (Monday to Friday)
            if ($currentDate->isWeekday()) {
                // Generate 3-8 appointments per day
                $appointmentsPerDay = rand(3, 8);

                for ($i = 0; $i < $appointmentsPerDay; $i++) {
                    $type = array_rand($appointmentTypes);
                    $typeData = $appointmentTypes[$type];

                    $appointments[] = [
                        'patient_id' => $patients->random()->id,
                        'dentist_id' => $dentists->random()->id,
                        'appointment_date' => $currentDate->copy(),
                        'appointment_time' => $timeSlots[array_rand($timeSlots)],
                        'duration' => $typeData['duration'],
                        'type' => $type,
                        'reason' => $typeData['reason'],
                        'status' => $statuses[array_rand($statuses)],
                        'notes' => $this->generateRandomNotes(),
                    ];
                }
            }

            $currentDate->addDay();
        }

        // Shuffle appointments to randomize the distribution
        shuffle($appointments);

        // Create appointments in database
        foreach ($appointments as $appointmentData) {
            $appointment = Appointment::create($appointmentData);

            // Attach random treatments to some appointments
            if (rand(0, 100) > 20) { // 80% chance to have treatments
                $randomTreatments = $treatments->random(rand(1, 2));
                foreach ($randomTreatments as $treatment) {
                    $appointment->treatments()->attach($treatment->id, [
                        'quantity' => 1,
                        'price' => $treatment->price,
                        'notes' => 'Standard treatment protocol',
                    ]);
                }
            }
        }
    }

    private function generateRandomNotes(): string
    {
        $notes = [
            'Patient prefers morning appointments',
            'Regular patient - no special requirements',
            'Patient is anxious about procedures',
            'First-time patient',
            'Prefers local anesthesia',
            'Patient has dental insurance',
            'Follow-up appointment scheduled',
            'Patient requested specific time slot',
            'No known allergies',
            'Patient prefers afternoon appointments',
            'Referred by another patient',
            'Emergency appointment',
            'Patient needs interpreter',
            'Continuing treatment plan',
            'Regular maintenance appointment',
        ];

        return $notes[array_rand($notes)];
    }
}
