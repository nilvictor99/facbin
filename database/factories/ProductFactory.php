<?php

namespace Database\Factories;

use App\Models\Currency;
use App\Models\MeasureUnit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'measure_unit_id' => MeasureUnit::inRandomOrder()->first()->id,
            'stock' => $this->faker->numberBetween(0, 1000),
            'sale_price' => $this->faker->randomFloat(2, 1, 1000),
            'purchase_price' => $this->faker->randomFloat(2, 0.5, 500),
            'active' => $this->faker->boolean(),
            'characters' => $this->faker->words(3),
            'instructions' => $this->faker->paragraph(),
            'currency_id' => Currency::inRandomOrder()->first()->id,
        ];
    }
}
