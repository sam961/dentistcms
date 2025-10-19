<?php

namespace Database\Factories;

use App\Models\Dentist;
use Illuminate\Database\Eloquent\Factories\Factory;

class DentistFactory extends Factory
{
    protected $model = Dentist::class;

    public function definition(): array
    {
        $specializations = [
            'General Dentistry',
            'Orthodontics',
            'Periodontics',
            'Endodontics',
            'Prosthodontics',
            'Oral Surgery',
            'Pediatric Dentistry',
            'Cosmetic Dentistry',
        ];

        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'specialization' => fake()->randomElement($specializations),
            'license_number' => fake()->numerify('DEN-#####'),
            'years_of_experience' => fake()->numberBetween(1, 30),
            'qualifications' => fake()->randomElement([
                'DDS - Doctor of Dental Surgery',
                'DMD - Doctor of Dental Medicine',
                'BDS, MDS - Orthodontics',
                'DDS, Certificate in Endodontics',
            ]),
            'working_days' => json_encode(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']),
            'working_hours_start' => '09:00',
            'working_hours_end' => '17:00',

        ];
    }
}
