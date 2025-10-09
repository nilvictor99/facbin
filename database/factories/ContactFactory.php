<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contact>
 */
class ContactFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $phones = [
            Branch::class,
            Customer::class,
        ];

        return [
            'contactable_type' => $this->faker->randomElement($phones),
            'contactable_id' => function (array $attributes) {
                return $attributes['contactable_type']::inRandomOrder()->first()->id;
            },
            'contact_type' => $this->faker->word(),
            'contact_value' => '9'.$this->faker->numerify('########'),
            'country_code' => '+51',
            'verified_at' => $this->faker->dateTime(),
        ];
    }
}
