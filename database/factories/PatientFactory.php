<?php

namespace Database\Factories;

use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Patient>
 */
class PatientFactory extends Factory
{
    protected $model = Patient::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $gender = fake()->randomElement(['male', 'female']);

        return [
            'first_name' => fake()->firstName($gender),
            'last_name' => fake()->lastName(),
            'date_of_birth' => fake()->dateTimeBetween('-80 years', '-18 years')->format('Y-m-d'),
            'gender' => $gender,
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'address' => fake()->streetAddress(),
            'city' => fake()->city(),
            // 'postal_code' => fake()->optional()->postcode(),
            'nationality' => fake()->optional()->country(),
            'emergency_contact_name' => fake()->name(),
            'emergency_contact_phone' => fake()->phoneNumber(),
            'medical_history' => fake()->optional()->paragraph(),
            'allergies' => fake()->optional()->randomElement([
                'Penicillin',
                'Latex',
                'Lidocaine',
                'None',
                'Aspirin, Ibuprofen',
            ]),
            'current_medications' => fake()->optional()->randomElement([
                'Blood pressure medication',
                'Diabetes medication',
                'None',
                'Aspirin daily',
            ]),
            'insurance_provider' => fake()->optional()->company(),
            'insurance_policy_number' => fake()->optional()->numerify('POL-########'),
        ];
    }
}
