<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class FakerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if ($this->command->confirm('¿Desea ejecutar el FakerSeeder?')) {
            \App\Models\Branch::factory()->count(5)->create([]);
            \App\Models\Company::factory()->count(5)->create([]);
            \App\Models\Customer::factory()->count(5)->create([]);
            \App\Models\Invoice::factory()->count(5)->create([]);
            \App\Models\Product::factory()->count(5)->create([]);
            \App\Models\Inventory::factory()->count(5)->create([]);
        }
    }
}
