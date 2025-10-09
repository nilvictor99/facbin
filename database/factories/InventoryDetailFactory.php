<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InventoryDetail>
 */
class InventoryDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'inventory_id' => $this->faker->randomNumber(),
            'product_id' => \App\Models\Product::inRandomOrder()->first()->id ?? \App\Models\Product::factory()->create()->id,
            'product_stock' => $this->faker->randomFloat(2, 0, 100),
            'starting_amount' => $this->faker->randomFloat(2, 0, 100),
            'ending_amount' => $this->faker->randomFloat(2, 0, 100),
            'difference' => $this->faker->randomFloat(2, -50, 50),
            'movement_type' => $this->faker->randomElement(\App\Enums\TypeMovementEnum::values()),
            'observation' => $this->faker->sentence,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
