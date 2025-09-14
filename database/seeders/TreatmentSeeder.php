<?php

namespace Database\Seeders;

use App\Models\Treatment;
use Illuminate\Database\Seeder;

class TreatmentSeeder extends Seeder
{
    public function run(): void
    {
        $treatments = [
            [
                'name' => 'Dental Cleaning',
                'description' => 'Professional teeth cleaning to remove plaque and tartar',
                'category' => 'Preventive',
                'price' => 150.00,
                'duration' => 30,
                'requires_followup' => false,
            ],
            [
                'name' => 'Tooth Extraction',
                'description' => 'Removal of a tooth from its socket in the bone',
                'category' => 'Surgical',
                'price' => 300.00,
                'duration' => 45,
                'requires_followup' => true,
            ],
            [
                'name' => 'Root Canal',
                'description' => 'Treatment to repair and save a badly damaged or infected tooth',
                'category' => 'Endodontic',
                'price' => 800.00,
                'duration' => 90,
                'requires_followup' => true,
            ],
            [
                'name' => 'Dental Filling',
                'description' => 'Restoration of a damaged or decayed tooth',
                'category' => 'Restorative',
                'price' => 200.00,
                'duration' => 30,
                'requires_followup' => false,
            ],
            [
                'name' => 'Teeth Whitening',
                'description' => 'Professional teeth whitening treatment',
                'category' => 'Cosmetic',
                'price' => 400.00,
                'duration' => 60,
                'requires_followup' => false,
            ],
            [
                'name' => 'Dental Crown',
                'description' => 'Cap placed over a tooth to restore its shape and size',
                'category' => 'Restorative',
                'price' => 1200.00,
                'duration' => 60,
                'requires_followup' => true,
            ],
            [
                'name' => 'Dental Bridge',
                'description' => 'Replacement of one or more missing teeth',
                'category' => 'Restorative',
                'price' => 2500.00,
                'duration' => 120,
                'requires_followup' => true,
            ],
            [
                'name' => 'Dental Implant',
                'description' => 'Surgical placement of artificial tooth root',
                'category' => 'Surgical',
                'price' => 3500.00,
                'duration' => 90,
                'requires_followup' => true,
            ],
            [
                'name' => 'Orthodontic Consultation',
                'description' => 'Initial consultation for braces or aligners',
                'category' => 'Orthodontic',
                'price' => 100.00,
                'duration' => 30,
                'requires_followup' => true,
            ],
            [
                'name' => 'Dental X-Ray',
                'description' => 'Radiographic examination of teeth and jaw',
                'category' => 'Diagnostic',
                'price' => 75.00,
                'duration' => 15,
                'requires_followup' => false,
            ],
        ];

        foreach ($treatments as $treatment) {
            Treatment::create($treatment);
        }
    }
}