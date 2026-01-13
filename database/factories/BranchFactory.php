<?php

namespace Database\Factories;

use App\Models\Addresse;
use App\Models\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Branch>
 */
class BranchFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => $this->faker->randomNumber(9, true),
            'name' => $this->faker->company,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function ($branch) {
            Addresse::factory()->count(1)->create([
                'addressable_type' => get_class($branch),
                'addressable_id' => $branch->id,
            ]);

            Contact::factory()->count(1)->create([
                'contactable_type' => get_class($branch),
                'contactable_id' => $branch->id,
            ]);
        });
    }
}
