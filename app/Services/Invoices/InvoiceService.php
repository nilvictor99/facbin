<?php

namespace App\Services\Invoices;

use App\Repositories\Models\InvoiceRepository;
use App\Services\Invoices\Formatters\BoletaFormatter;
use App\Services\Invoices\Formatters\FacturaFormatter;
use Greenter\Model\Sale\Invoice as GreenterInvoice;
use Greenter\See;
use Greenter\Ws\Services\SunatEndpoints;
use Illuminate\Support\Facades\Storage;

class InvoiceService
{
    private See $see;

    private InvoiceRepository $invoiceRepo;

    private BoletaFormatter $boletaFormatter;

    private FacturaFormatter $facturaFormatter;

    public function __construct(
        InvoiceRepository $invoiceRepo,
        BoletaFormatter $boletaFormatter,
        FacturaFormatter $facturaFormatter
    ) {
        $this->invoiceRepo = $invoiceRepo;
        $this->boletaFormatter = $boletaFormatter;
        $this->facturaFormatter = $facturaFormatter;
        $this->configureGreenter();
    }

    private function configureGreenter(): void
    {
        $this->see = new See;
        $this->see->setCertificate(file_get_contents(Storage::disk('certificates')->path(config('invoice.certificate_path'))));
        $this->see->setService(
            config('invoice.environment') === 'production' ? SunatEndpoints::FE_PRODUCCION : SunatEndpoints::FE_BETA
        );
        $this->see->setClaveSOL(
            config('invoice.ruc'),
            config('invoice.user'),
            config('invoice.password')
        );
    }

    public function createInvoice(array $data = []): array
    {
        $greenterInvoice = $this->buildGreenterInvoice($data);

        /** @var BillResult $result */
        $result = $this->see->send($greenterInvoice);

        $xmlPath = 'invoices/'.$greenterInvoice->getName().'.xml';
        Storage::put($xmlPath, $this->see->getFactory()->getLastXml());

        if (! $result->isSuccess()) {
            return [
                'success' => false,
                'error' => $result->getError()->getMessage(),
                'code' => $result->getError()->getCode(),
            ];
        }

        $cdr = $result->getCdrResponse();
        $code = (int) $cdr->getCode();
        $status = $code === 0 ? 'aceptado' : 'rechazado';

        $cdrPath = 'cdrs/R-'.$greenterInvoice->getName().'.zip';
        Storage::put($cdrPath, $result->getCdrZip());

        $invoice = $this->invoiceRepo->create([
            'serie' => $greenterInvoice->getSerie(),
            'correlativo' => $greenterInvoice->getCorrelativo(),
            'tipo_doc' => $greenterInvoice->getTipoDoc(),
            'fecha_emision' => $greenterInvoice->getFechaEmision(),
            'total' => $greenterInvoice->getMtoImpVenta(),
            'estado' => $status,
            'xml_path' => $xmlPath,
            'cdr_path' => $cdrPath,
        ]);

        return [
            'success' => true,
            'data' => [
                'invoice_id' => $invoice->id,
                'status' => $status,
                'xml_path' => $xmlPath,
                'cdr_path' => $cdrPath,
                'sunat_response' => [
                    'code' => $code,
                    'description' => $cdr->getDescription(),
                    'notes' => $cdr->getNotes(),
                ],
            ],
        ];
    }

    private function buildGreenterInvoice(array $data): GreenterInvoice
    {
        return empty($data['tipo_doc']) || $data['tipo_doc'] === '01'
            ? $this->facturaFormatter->formatFactura($data)
            : $this->boletaFormatter->formatBoleta($data);
    }
}
