<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Dentist;
use App\Models\Patient;
use App\Models\Treatment;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TenantDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🌱 Starting tenant data seeding...');

        $tenant = \App\Models\Tenant::first();

        if (! $tenant) {
            $this->command->error('❌ No tenant found! Please create a tenant first.');

            return;
        }

        $this->command->info("✅ Seeding data for tenant: {$tenant->name}");

        // Admin user
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@'.($tenant->subdomain ?? 'clinic').'.com'],
            [
                'name' => 'Dr. Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'tenant_id' => $tenant->id,
                'is_super_admin' => false,
                'email_verified_at' => now(),
            ]
        );

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
                    'first_name' => fake()->firstName(),
                    'last_name' => fake()->lastName(),
                    'email' => fake()->unique()->safeEmail(),
                    'phone' => fake()->phoneNumber(),
                    'license_number' => fake()->numerify('DEN-#####'),
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
                'first_name' => fake()->firstName(),
                'last_name' => fake()->lastName(),
                'date_of_birth' => fake()->dateTimeBetween('-60 years', '-18 years')->format('Y-m-d'),
                'gender' => fake()->randomElement(['male', 'female']),
                'email' => fake()->unique()->safeEmail(),
                'phone' => fake()->phoneNumber(),
                'address' => fake()->streetAddress(),
                'city' => fake()->city(),
                'emergency_contact_name' => fake()->name(),
                'emergency_contact_phone' => fake()->phoneNumber(),
                'tenant_id' => $tenant->id,
            ]);
        }

        // Appointments
        $appointmentCount = 0;
        foreach ($patients as $patient) {
            for ($i = 0; $i < 2; $i++) {
                $appointmentDate = fake()->dateTimeBetween('-3 months', '+2 months');
                $status = $appointmentDate < now() ? 'completed' : 'scheduled';

                Appointment::create([
                    'patient_id' => $patient->id,
                    'dentist_id' => fake()->randomElement($dentists)->id,
                    'appointment_date' => $appointmentDate->format('Y-m-d'),
                    'appointment_time' => fake()->randomElement(['09:00', '10:00', '14:00', '15:00']),
                    'duration' => 30,
                    'status' => $status,
                    'type' => fake()->randomElement(['checkup', 'follow-up', 'emergency']),
                    'reason' => fake()->sentence(),
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
