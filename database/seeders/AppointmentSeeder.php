<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Dentist;
use App\Models\Treatment;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

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

        $appointments = [
            // Today's appointments
            [
                'patient_id' => $patients->random()->id,
                'dentist_id' => $dentists->random()->id,
                'appointment_date' => Carbon::today(),
                'appointment_time' => '09:00',
                'duration' => 30,
                'type' => 'Regular Checkup',
                'reason' => 'Routine dental examination and cleaning',
                'status' => 'confirmed',
                'notes' => 'Patient prefers morning appointments',
            ],
            [
                'patient_id' => $patients->random()->id,
                'dentist_id' => $dentists->random()->id,
                'appointment_date' => Carbon::today(),
                'appointment_time' => '10:30',
                'duration' => 45,
                'type' => 'Treatment',
                'reason' => 'Dental filling for upper molar',
                'status' => 'confirmed',
                'notes' => 'Patient is anxious about procedures',
            ],
            [
                'patient_id' => $patients->random()->id,
                'dentist_id' => $dentists->random()->id,
                'appointment_date' => Carbon::today(),
                'appointment_time' => '14:00',
                'duration' => 60,
                'type' => 'Consultation',
                'reason' => 'Orthodontic evaluation',
                'status' => 'scheduled',
                'notes' => 'Considering braces treatment',
            ],
            
            // Tomorrow's appointments
            [
                'patient_id' => $patients->random()->id,
                'dentist_id' => $dentists->random()->id,
                'appointment_date' => Carbon::tomorrow(),
                'appointment_time' => '08:30',
                'duration' => 90,
                'type' => 'Surgery',
                'reason' => 'Wisdom tooth extraction',
                'status' => 'confirmed',
                'notes' => 'Pre-operative instructions given',
            ],
            [
                'patient_id' => $patients->random()->id,
                'dentist_id' => $dentists->random()->id,
                'appointment_date' => Carbon::tomorrow(),
                'appointment_time' => '11:00',
                'duration' => 30,
                'type' => 'Follow-up',
                'reason' => 'Post-treatment checkup',
                'status' => 'scheduled',
                'notes' => 'Check healing progress',
            ],

            // This week's appointments
            [
                'patient_id' => $patients->random()->id,
                'dentist_id' => $dentists->random()->id,
                'appointment_date' => Carbon::now()->addDays(3),
                'appointment_time' => '15:30',
                'duration' => 60,
                'type' => 'Treatment',
                'reason' => 'Root canal therapy',
                'status' => 'scheduled',
                'notes' => 'Second session of treatment',
            ],
            [
                'patient_id' => $patients->random()->id,
                'dentist_id' => $dentists->random()->id,
                'appointment_date' => Carbon::now()->addDays(5),
                'appointment_time' => '13:00',
                'duration' => 45,
                'type' => 'Cleaning',
                'reason' => 'Professional teeth cleaning',
                'status' => 'scheduled',
                'notes' => 'Regular 6-month cleaning',
            ],
            [
                'patient_id' => $patients->random()->id,
                'dentist_id' => $dentists->random()->id,
                'appointment_date' => Carbon::now()->addDays(7),
                'appointment_time' => '10:00',
                'duration' => 120,
                'type' => 'Surgery',
                'reason' => 'Dental implant placement',
                'status' => 'scheduled',
                'notes' => 'First implant session',
            ],

            // Next week's appointments
            [
                'patient_id' => $patients->random()->id,
                'dentist_id' => $dentists->random()->id,
                'appointment_date' => Carbon::now()->addWeeks(1)->addDays(2),
                'appointment_time' => '09:30',
                'duration' => 30,
                'type' => 'Checkup',
                'reason' => 'Regular dental examination',
                'status' => 'scheduled',
                'notes' => 'New patient intake',
            ],
            [
                'patient_id' => $patients->random()->id,
                'dentist_id' => $dentists->random()->id,
                'appointment_date' => Carbon::now()->addWeeks(1)->addDays(4),
                'appointment_time' => '16:00',
                'duration' => 75,
                'type' => 'Cosmetic',
                'reason' => 'Teeth whitening treatment',
                'status' => 'scheduled',
                'notes' => 'Patient requested whitening consultation',
            ],
        ];

        foreach ($appointments as $appointmentData) {
            $appointment = Appointment::create($appointmentData);
            
            // Attach random treatments to some appointments
            if (rand(0, 100) > 30) { // 70% chance to have treatments
                $randomTreatments = $treatments->random(rand(1, 3));
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
}