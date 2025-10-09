<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\Customer;
use App\Models\Ubigeo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Addresse>
 */
class AddresseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $addressable = [
            Branch::class,
            Customer::class,
        ];

        return [
            'addressable_type' => $this->faker->randomElement($addressable),
            'addressable_id' => function (array $attributes) {
                $model = $attributes['addressable_type']::inRandomOrder()->first();

                return $model ? $model->id : null;
            },
            'ubigeo_cod' => Ubigeo::inRandomOrder()->first()?->cod_ubigeo,
            'address' => $this->faker->address,
            'reference' => $this->faker->text(50),
        ];
    }
}
