<?php

namespace App\Console\Commands;

use App\Services\Invoices\InvoiceService;
use Illuminate\Console\Command;

class TestInvoiceCommand extends Command
{
    protected $signature = 'test:invoice';

    protected $description = 'Enviar una factura de prueba a SUNAT usando datos por defecto de los formateadores';

    public function handle(InvoiceService $service)
    {
        $result = $service->createInvoice();

        if ($result['success']) {
            $this->info('Factura enviada exitosamente!');
            $this->info('Estado: '.$result['data']['status']);
            $this->info('XML: '.$result['data']['xml_path']);
            $this->info('CDR: '.$result['data']['cdr_path']);
        } else {
            $this->error('Error: '.$result['error']);
        }
    }
}
