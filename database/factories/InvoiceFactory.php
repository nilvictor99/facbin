<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'serie' => 'F'.str_pad($this->faker->numberBetween(1, 999), 3, '0', STR_PAD_LEFT),
            'correlativo' => $this->faker->unique()->numberBetween(1, 10000),
            'tipo_doc' => $this->faker->randomElement(['01', '03']),
            'fecha_emision' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'subtotal' => $subtotal = $this->faker->randomFloat(2, 10, 1000),
            'igv_percentage' => 18.0,
            'igv' => $subtotal * 0.18,
            'total' => $subtotal * 1.18,
            'estado' => $this->faker->randomElement(['emitido', 'anulado']),
            'customer_id' => Customer::inRandomOrder()->first()->id,
            'company_id' => Company::inRandomOrder()->first()->id,
            'xml_path' => null,
            'cdr_path' => null,
            'ticket' => $this->faker->uuid(),
            'doc_respuesta' => null,
            'cadena_ticket' => $this->faker->text(100),
            'url_ticket' => $this->faker->url(),
            'url_a4' => $this->faker->url(),
            'cadena_xml' => $this->faker->text(200),
            'url_xml' => $this->faker->url(),
        ];
    }
}
