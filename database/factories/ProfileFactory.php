<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\IdentificationType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profile>
 */
class ProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $profileable = [
            Customer::class,
        ];

        return [
            'profileable_type' => $this->faker->randomElement($profileable),
            'profileable_id' => function (array $attributes) {
                return $attributes['profileable_type']::factory()->create()->id;
            },
            'identification_type_id' => IdentificationType::inRandomOrder()->first()->id,
            'document_number' => $this->faker->unique()->numerify('########'),
            'name' => $this->faker->firstName(),
            'paternal_surname' => $this->faker->lastName(),
            'maternal_surname' => $this->faker->lastName(),
            'full_name' => $this->faker->name(),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'date_of_birth' => $this->faker->date(),
            'civil_status' => $this->faker->randomElement(['single', 'married', 'divorced', 'widowed']),
            'education_level' => $this->faker->randomElement(['primary', 'secondary', 'tertiary']),
            'blood_type' => $this->faker->randomElement(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']),
            'description' => $this->faker->sentence(),
            'adicional_data' => $this->faker->randomElements(['key1' => 'value1', 'key2' => 'value2'], 2),
            'characteristics' => $this->faker->randomElements(['trait1', 'trait2', 'trait3'], 2),
            'photo' => $this->faker->imageUrl(),
            'active' => $this->faker->boolean(),
        ];
    }
}
