<?php

namespace Database\Seeders;

use App\Models\DocumentType;
use Illuminate\Database\Seeder;

class DocumentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $documentTypes = [
            ['code' => '77', 'name' => 'NOTA DE VENTA', 'description' => 'Nota de Venta'],
            ['code' => '03', 'name' => 'BOLETA', 'description' => 'Boleta'],
            ['code' => '01', 'name' => 'FACTURA', 'description' => 'Factura'],
        ];

        foreach ($documentTypes as $documentType) {
            DocumentType::create($documentType);
        }
    }
}
