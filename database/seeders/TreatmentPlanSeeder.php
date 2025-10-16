<?php

namespace Database\Seeders;

use App\Models\Dentist;
use App\Models\Patient;
use App\Models\Tenant;
use App\Models\Treatment;
use App\Models\TreatmentPlan;
use App\Models\TreatmentPlanItem;
use Illuminate\Database\Seeder;

class TreatmentPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first tenant (or create one if needed)
        $tenant = Tenant::first();

        if (! $tenant) {
            $this->command->error('No tenant found. Please create a tenant first.');

            return;
        }

        $this->command->info("Seeding data for tenant: {$tenant->name}");

        // Create Dentists
        $this->command->info('Creating dentists...');
        $dentists = [
            [
                'tenant_id' => $tenant->id,
                'first_name' => 'Sarah',
                'last_name' => 'Johnson',
                'email' => 'sarah.johnson@dentistcms.test',
                'phone' => '+1-555-0101',
                'specialization' => 'General Dentistry',
                'license_number' => 'DDS-2024-001',
                'years_of_experience' => 12,
                'status' => 'active',
                'working_days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'],
            ],
            [
                'tenant_id' => $tenant->id,
                'first_name' => 'Michael',
                'last_name' => 'Chen',
                'email' => 'michael.chen@dentistcms.test',
                'phone' => '+1-555-0102',
                'specialization' => 'Orthodontics',
                'license_number' => 'DDS-2024-002',
                'years_of_experience' => 8,
                'status' => 'active',
                'working_days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'],
            ],
            [
                'tenant_id' => $tenant->id,
                'first_name' => 'Emily',
                'last_name' => 'Rodriguez',
                'email' => 'emily.rodriguez@dentistcms.test',
                'phone' => '+1-555-0103',
                'specialization' => 'Periodontics',
                'license_number' => 'DDS-2024-003',
                'years_of_experience' => 15,
                'status' => 'active',
                'working_days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'],
            ],
        ];

        foreach ($dentists as $dentistData) {
            Dentist::firstOrCreate(
                ['email' => $dentistData['email']],
                $dentistData
            );
        }

        $this->command->info('✓ Created 3 dentists');

        // Create Patients
        $this->command->info('Creating patients...');
        $patients = [
            [
                'tenant_id' => $tenant->id,
                'first_name' => 'John',
                'last_name' => 'Smith',
                'email' => 'john.smith@example.com',
                'phone' => '+1-555-1001',
                'date_of_birth' => '1985-03-15',
                'gender' => 'male',
                'nationality' => 'American',
                'address' => '123 Main Street',
                'city' => 'New York, NY 10001',
                'medical_history' => 'No significant medical history',
                'allergies' => 'Penicillin',
                'current_medications' => 'None',
                'insurance_provider' => 'Blue Cross Blue Shield',
                'insurance_policy_number' => 'BCBS-123456789',
                'emergency_contact_name' => 'Jane Smith',
                'emergency_contact_phone' => '+1-555-1000',
            ],
            [
                'tenant_id' => $tenant->id,
                'first_name' => 'Emma',
                'last_name' => 'Davis',
                'email' => 'emma.davis@example.com',
                'phone' => '+1-555-1002',
                'date_of_birth' => '1990-07-22',
                'gender' => 'female',
                'nationality' => 'American',
                'address' => '456 Oak Avenue',
                'city' => 'Los Angeles, CA 90001',
                'medical_history' => 'Type 2 Diabetes',
                'allergies' => 'None',
                'current_medications' => 'Metformin',
                'insurance_provider' => 'Aetna',
                'insurance_policy_number' => 'AET-987654321',
                'emergency_contact_name' => 'Robert Davis',
                'emergency_contact_phone' => '+1-555-1003',
            ],
        ];

        foreach ($patients as $patientData) {
            Patient::firstOrCreate(
                ['email' => $patientData['email']],
                $patientData
            );
        }

        $this->command->info('✓ Created 2 patients');

        // Create Treatments
        $this->command->info('Creating treatments...');
        $treatments = [
            [
                'tenant_id' => $tenant->id,
                'name' => 'Dental Cleaning',
                'description' => 'Professional teeth cleaning and polishing',
                'category' => 'Preventive',
                'price' => 120.00,
                'duration' => 30,
                'is_active' => true,
            ],
            [
                'tenant_id' => $tenant->id,
                'name' => 'Dental Filling',
                'description' => 'Composite resin filling for cavity',
                'category' => 'Restorative',
                'price' => 250.00,
                'duration' => 45,
                'is_active' => true,
            ],
            [
                'tenant_id' => $tenant->id,
                'name' => 'Root Canal',
                'description' => 'Root canal therapy for infected tooth',
                'category' => 'Endodontics',
                'price' => 1200.00,
                'duration' => 90,
                'requires_followup' => true,
                'is_active' => true,
            ],
            [
                'tenant_id' => $tenant->id,
                'name' => 'Dental Crown',
                'description' => 'Porcelain crown restoration',
                'category' => 'Restorative',
                'price' => 1500.00,
                'duration' => 60,
                'requires_followup' => true,
                'is_active' => true,
            ],
            [
                'tenant_id' => $tenant->id,
                'name' => 'Teeth Whitening',
                'description' => 'Professional teeth whitening treatment',
                'category' => 'Cosmetic',
                'price' => 500.00,
                'duration' => 60,
                'is_active' => true,
            ],
            [
                'tenant_id' => $tenant->id,
                'name' => 'Dental Implant',
                'description' => 'Single tooth implant placement',
                'category' => 'Oral Surgery',
                'price' => 3500.00,
                'duration' => 120,
                'requires_followup' => true,
                'is_active' => true,
            ],
            [
                'tenant_id' => $tenant->id,
                'name' => 'X-Ray (Full Mouth)',
                'description' => 'Complete dental x-ray series',
                'category' => 'Diagnostic',
                'price' => 150.00,
                'duration' => 15,
                'is_active' => true,
            ],
            [
                'tenant_id' => $tenant->id,
                'name' => 'Tooth Extraction',
                'description' => 'Simple tooth extraction',
                'category' => 'Oral Surgery',
                'price' => 300.00,
                'duration' => 30,
                'is_active' => true,
            ],
        ];

        foreach ($treatments as $treatmentData) {
            Treatment::firstOrCreate(
                ['tenant_id' => $tenant->id, 'name' => $treatmentData['name']],
                $treatmentData
            );
        }

        $this->command->info('✓ Created 8 treatments');

        // Create Treatment Plans
        $this->command->info('Creating treatment plans...');

        $patient = Patient::where('tenant_id', $tenant->id)->where('email', 'john.smith@example.com')->first();
        $dentist = Dentist::where('tenant_id', $tenant->id)->where('email', 'sarah.johnson@dentistcms.test')->first();

        if ($patient && $dentist) {
            // Treatment Plan 1: Comprehensive Dental Restoration (Immediate)
            $plan1 = TreatmentPlan::create([
                'tenant_id' => $tenant->id,
                'patient_id' => $patient->id,
                'dentist_id' => $dentist->id,
                'title' => 'Comprehensive Dental Restoration',
                'description' => 'Complete treatment plan for restoring oral health including root canal, crown, and fillings.',
                'phase' => 'immediate',
                'status' => 'accepted',
                'priority' => 1,
                'presented_date' => now()->subDays(5),
                'accepted_date' => now()->subDays(3),
                'notes' => 'Patient has dental insurance that covers 80% of restorative procedures.',
            ]);

            // Add items to plan 1
            $rootCanal = Treatment::where('name', 'Root Canal')->first();
            $crown = Treatment::where('name', 'Dental Crown')->first();
            $filling = Treatment::where('name', 'Dental Filling')->first();
            $xray = Treatment::where('name', 'X-Ray (Full Mouth)')->first();

            TreatmentPlanItem::create([
                'treatment_plan_id' => $plan1->id,
                'treatment_id' => $xray->id,
                'tooth_number' => null,
                'tooth_surface' => null,
                'quantity' => 1,
                'unit_cost' => $xray->price,
                'total_cost' => $xray->price,
                'insurance_estimate' => $xray->price * 0.8,
                'status' => 'completed',
                'order' => 1,
                'notes' => 'Initial diagnostic x-rays',
                'completed_date' => now()->subDays(3),
            ]);

            TreatmentPlanItem::create([
                'treatment_plan_id' => $plan1->id,
                'treatment_id' => $rootCanal->id,
                'tooth_number' => '14',
                'tooth_surface' => 'Full',
                'quantity' => 1,
                'unit_cost' => $rootCanal->price,
                'total_cost' => $rootCanal->price,
                'insurance_estimate' => $rootCanal->price * 0.8,
                'status' => 'in_progress',
                'order' => 2,
                'notes' => 'Root canal on upper right first molar',
            ]);

            TreatmentPlanItem::create([
                'treatment_plan_id' => $plan1->id,
                'treatment_id' => $crown->id,
                'tooth_number' => '14',
                'tooth_surface' => 'Full',
                'quantity' => 1,
                'unit_cost' => $crown->price,
                'total_cost' => $crown->price,
                'insurance_estimate' => $crown->price * 0.8,
                'status' => 'pending',
                'order' => 3,
                'notes' => 'Crown to be placed after root canal completion',
            ]);

            TreatmentPlanItem::create([
                'treatment_plan_id' => $plan1->id,
                'treatment_id' => $filling->id,
                'tooth_number' => '15',
                'tooth_surface' => 'Occlusal',
                'quantity' => 1,
                'unit_cost' => $filling->price,
                'total_cost' => $filling->price,
                'insurance_estimate' => $filling->price * 0.8,
                'status' => 'pending',
                'order' => 4,
                'notes' => 'Small cavity on adjacent tooth',
            ]);

            TreatmentPlanItem::create([
                'treatment_plan_id' => $plan1->id,
                'treatment_id' => $filling->id,
                'tooth_number' => '30',
                'tooth_surface' => 'Mesial-Occlusal',
                'quantity' => 1,
                'unit_cost' => $filling->price,
                'total_cost' => $filling->price,
                'insurance_estimate' => $filling->price * 0.8,
                'status' => 'pending',
                'order' => 5,
                'notes' => 'Cavity on lower right first molar',
            ]);

            $plan1->refresh();
            $plan1->recalculateTotals();

            $this->command->info("✓ Created treatment plan 1: {$plan1->title}");
            $this->command->info("  - Total Cost: \${$plan1->total_cost}");
            $this->command->info("  - Insurance Coverage: \${$plan1->insurance_coverage}");
            $this->command->info("  - Patient Portion: \${$plan1->patient_portion}");
            $this->command->info("  - Progress: {$plan1->progress_percentage}%");

            // Treatment Plan 2: Cosmetic Enhancement (Optional)
            $plan2 = TreatmentPlan::create([
                'tenant_id' => $tenant->id,
                'patient_id' => $patient->id,
                'dentist_id' => $dentist->id,
                'title' => 'Cosmetic Smile Enhancement',
                'description' => 'Optional cosmetic procedures to enhance smile appearance.',
                'phase' => 'optional',
                'status' => 'presented',
                'priority' => 3,
                'presented_date' => now()->subDays(2),
                'notes' => 'Elective procedures not covered by insurance. Patient to decide after completing restorative work.',
            ]);

            $whitening = Treatment::where('name', 'Teeth Whitening')->first();

            TreatmentPlanItem::create([
                'treatment_plan_id' => $plan2->id,
                'treatment_id' => $whitening->id,
                'tooth_number' => null,
                'tooth_surface' => null,
                'quantity' => 1,
                'unit_cost' => $whitening->price,
                'total_cost' => $whitening->price,
                'insurance_estimate' => 0,
                'status' => 'pending',
                'order' => 1,
                'notes' => 'Professional whitening treatment - cosmetic procedure',
            ]);

            $plan2->refresh();
            $plan2->recalculateTotals();

            $this->command->info("✓ Created treatment plan 2: {$plan2->title}");
            $this->command->info("  - Total Cost: \${$plan2->total_cost}");
            $this->command->info("  - Patient Portion: \${$plan2->patient_portion} (no insurance coverage)");
        }

        $this->command->info('');
        $this->command->info('=================================');
        $this->command->info('✓ Seeding completed successfully!');
        $this->command->info('=================================');
        $this->command->info("Patient: {$patient->full_name}");
        $this->command->info("Primary Dentist: {$dentist->full_name}");
        $this->command->info('Treatment Plans: 2');
        $this->command->info('');
    }
}
