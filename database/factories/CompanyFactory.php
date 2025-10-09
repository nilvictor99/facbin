<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ruc' => $this->faker->unique()->numerify('###########'),
            'business_name' => $this->faker->company(),
            'trade_name' => $this->faker->company(),
            'address' => $this->faker->address(),
            'ubigeo' => $this->faker->numerify('######'),
            'urbanization' => $this->faker->city(),
            'phone' => $this->faker->phoneNumber(),
            'website' => $this->faker->url(),
            'founding_date' => $this->faker->date(),
            'industry' => $this->faker->word(),
            'status' => $this->faker->randomElement(['active', 'inactive']),
            'logo' => $this->faker->imageUrl(),
            'primary_contact' => $this->faker->name(),
            'sponsor' => $this->faker->name(),
            'default_shipping_mode' => $this->faker->randomElement(['air', 'sea', 'land']),
            'user_id' => User::inRandomOrder()->first()->id,
        ];
    }
}
