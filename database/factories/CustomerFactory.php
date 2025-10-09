<?php

namespace Database\Factories;

use App\Models\Addresse;
use App\Models\Contact;
use App\Models\Profile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->regexify('[A-Z]{3}[0-9]{3}'),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function ($customer) {
            Addresse::factory()->count(1)->create([
                'addressable_type' => get_class($customer),
                'addressable_id' => $customer->id,
            ]);

            Contact::factory()->count(1)->create([
                'contactable_type' => get_class($customer),
                'contactable_id' => $customer->id,
            ]);

            Profile::factory()->state([
                'profileable_type' => get_class($customer),
                'profileable_id' => $customer->id,
            ])->create();
        });
    }
}
