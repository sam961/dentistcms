<?php

namespace Database\Seeders;

use App\Models\Dentist;
use App\Models\Patient;
use App\Models\ToothRecord;
use App\Models\Treatment;
use Illuminate\Database\Seeder;

class ToothRecordSeeder extends Seeder
{
    public function run(): void
    {
        $patients = Patient::all();
        $dentists = Dentist::all();
        $treatments = Treatment::all();

        if ($patients->isEmpty() || $dentists->isEmpty()) {
            return;
        }

        $conditions = ['healthy', 'cavity', 'filled', 'crown', 'root_canal', 'implant'];
        $surfaces = ['mesial', 'distal', 'occlusal', 'buccal', 'lingual'];
        $severities = ['mild', 'moderate', 'severe'];

        foreach ($patients->take(5) as $patient) {
            // Create some tooth records for each patient
            $teethToRecord = fake()->randomElements(range(1, 32), fake()->numberBetween(3, 8));

            foreach ($teethToRecord as $toothNumber) {
                $condition = fake()->randomElement($conditions);
                $treatmentDate = $condition !== 'healthy' ? fake()->dateTimeBetween('-2 years', 'now') : null;

                ToothRecord::create([
                    'patient_id' => $patient->id,
                    'tooth_number' => (string) $toothNumber,
                    'tooth_type' => 'permanent',
                    'condition' => $condition,
                    'notes' => $condition !== 'healthy' ? fake()->sentence() : null,
                    'treatment_date' => $treatmentDate,
                    'treatment_id' => $condition !== 'healthy' && $treatments->isNotEmpty() ? $treatments->random()->id : null,
                    'dentist_id' => $condition !== 'healthy' && $dentists->isNotEmpty() ? $dentists->random()->id : null,
                    'surface' => $condition === 'cavity' || $condition === 'filled' ? fake()->randomElement($surfaces) : null,
                    'severity' => $condition === 'cavity' ? fake()->randomElement($severities) : null,
                ]);

                // Add history for some teeth (multiple records)
                if ($condition !== 'healthy' && fake()->boolean(30)) {
                    ToothRecord::create([
                        'patient_id' => $patient->id,
                        'tooth_number' => (string) $toothNumber,
                        'tooth_type' => 'permanent',
                        'condition' => 'cavity',
                        'notes' => 'Initial cavity detected',
                        'treatment_date' => fake()->dateTimeBetween('-3 years', '-2 years'),
                        'treatment_id' => $treatments->isNotEmpty() ? $treatments->random()->id : null,
                        'dentist_id' => $dentists->isNotEmpty() ? $dentists->random()->id : null,
                        'surface' => fake()->randomElement($surfaces),
                        'severity' => fake()->randomElement($severities),
                    ]);
                }
            }
        }
    }
}
