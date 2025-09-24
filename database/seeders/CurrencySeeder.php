<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $action = 'add';
        $currency = 'PEN,USD';

        Artisan::call('currency:manage', [
            'action' => $action,
            'currency' => $currency,
        ]);

        $output = Artisan::output();
        $this->command->info($output);
    }
}
