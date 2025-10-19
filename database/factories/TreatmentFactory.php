<?php

namespace Database\Factories;

use App\Models\Treatment;
use Illuminate\Database\Eloquent\Factories\Factory;

class TreatmentFactory extends Factory
{
    protected $model = Treatment::class;

    public function definition(): array
    {
        $treatments = [
            ['name' => 'Dental Cleaning', 'price' => 100, 'duration' => 30, 'category' => 'Preventive'],
            ['name' => 'Dental Filling', 'price' => 150, 'duration' => 45, 'category' => 'Restorative'],
            ['name' => 'Root Canal', 'price' => 800, 'duration' => 90, 'category' => 'Endodontics'],
            ['name' => 'Tooth Extraction', 'price' => 200, 'duration' => 30, 'category' => 'Oral Surgery'],
            ['name' => 'Dental Crown', 'price' => 1200, 'duration' => 60, 'category' => 'Restorative'],
            ['name' => 'Teeth Whitening', 'price' => 400, 'duration' => 60, 'category' => 'Cosmetic'],
            ['name' => 'Braces Consultation', 'price' => 100, 'duration' => 45, 'category' => 'Orthodontics'],
            ['name' => 'Dental Implant', 'price' => 3000, 'duration' => 120, 'category' => 'Restorative'],
            ['name' => 'Gum Treatment', 'price' => 500, 'duration' => 60, 'category' => 'Periodontics'],
            ['name' => 'X-Ray', 'price' => 80, 'duration' => 15, 'category' => 'Diagnostic'],
        ];

        $treatment = fake()->randomElement($treatments);

        return [
            'name' => $treatment['name'],
            'description' => fake()->sentence(),
            'price' => $treatment['price'],
            'duration_minutes' => $treatment['duration'],
            'category' => $treatment['category'],
            'requires_followup' => fake()->boolean(30),

        ];
    }
}
