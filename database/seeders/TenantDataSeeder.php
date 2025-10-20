<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Dentist;
use App\Models\Patient;
use App\Models\Treatment;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class TenantDataSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $this->command->info('🌱 Starting tenant data seeding...');

        // Only seed for demo tenant
        $tenant = \App\Models\Tenant::where('subdomain', 'demo')->first();

        if (! $tenant) {
            $this->command->error('❌ Demo tenant not found! Run DemoAdminSeeder first.');

            return;
        }

        $this->command->info("✅ Seeding data for demo tenant: {$tenant->name}");

        // Treatments
        $treatments = [
            ['name' => 'Dental Cleaning', 'price' => 100, 'duration' => 30, 'category' => 'Preventive'],
            ['name' => 'Dental Filling', 'price' => 150, 'duration' => 45, 'category' => 'Restorative'],
            ['name' => 'Root Canal', 'price' => 800, 'duration' => 90, 'category' => 'Endodontics'],
            ['name' => 'Tooth Extraction', 'price' => 200, 'duration' => 30, 'category' => 'Oral Surgery'],
            ['name' => 'Dental Crown', 'price' => 1200, 'duration' => 60, 'category' => 'Restorative'],
        ];

        $createdTreatments = [];
        foreach ($treatments as $t) {
            $createdTreatments[] = Treatment::firstOrCreate(
                ['name' => $t['name'], 'tenant_id' => $tenant->id],
                [...$t, 'description' => "Professional {$t['name']} service", 'requires_followup' => false]
            );
        }

        // Dentists
        $specializations = ['General Dentistry', 'Orthodontics', 'Endodontics'];
        $dentists = [];
        foreach ($specializations as $spec) {
            $dentists[] = Dentist::firstOrCreate(
                ['specialization' => $spec, 'tenant_id' => $tenant->id],
                [
                    'first_name' => $faker->firstName(),
                    'last_name' => $faker->lastName(),
                    'email' => $faker->unique()->safeEmail(),
                    'phone' => $faker->phoneNumber(),
                    'license_number' => $faker->numerify('DEN-#####'),
                    'years_of_experience' => rand(5, 20),
                    'qualifications' => 'DDS',
                    'working_days' => json_encode(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']),
                    'working_hours_start' => '09:00',
                    'working_hours_end' => '17:00',
                ]
            );
        }

        // Patients
        $patients = [];
        for ($i = 0; $i < 30; $i++) {
            $patients[] = Patient::create([
                'first_name' => $faker->firstName(),
                'last_name' => $faker->lastName(),
                'date_of_birth' => $faker->dateTimeBetween('-60 years', '-18 years')->format('Y-m-d'),
                'gender' => $faker->randomElement(['male', 'female']),
                'email' => $faker->unique()->safeEmail(),
                'phone' => $faker->phoneNumber(),
                'address' => $faker->streetAddress(),
                'city' => $faker->city(),
                'emergency_contact_name' => $faker->name(),
                'emergency_contact_phone' => $faker->phoneNumber(),
                'tenant_id' => $tenant->id,
            ]);
        }

        // Appointments
        $appointmentCount = 0;
        foreach ($patients as $patient) {
            for ($i = 0; $i < 2; $i++) {
                $appointmentDate = $faker->dateTimeBetween('-3 months', '+2 months');
                $status = $appointmentDate < now() ? 'completed' : 'scheduled';

                Appointment::create([
                    'patient_id' => $patient->id,
                    'dentist_id' => $faker->randomElement($dentists)->id,
                    'appointment_date' => $appointmentDate->format('Y-m-d'),
                    'appointment_time' => $faker->randomElement(['09:00', '10:00', '14:00', '15:00']),
                    'duration' => 30,
                    'status' => $status,
                    'type' => $faker->randomElement(['checkup', 'follow-up', 'emergency']),
                    'reason' => $faker->sentence(),
                    'tenant_id' => $tenant->id,
                ]);
                $appointmentCount++;
            }
        }

        $this->command->info('✅ Seeding completed!');
        $this->command->table(
            ['Resource', 'Count'],
            [
                ['Treatments', count($createdTreatments)],
                ['Dentists', count($dentists)],
                ['Patients', count($patients)],
                ['Appointments', $appointmentCount],
            ]
        );
    }
}
