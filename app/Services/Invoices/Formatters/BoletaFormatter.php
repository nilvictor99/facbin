<?php

namespace App\Services\Invoices\Formatters;

use Greenter\Model\Client\Client as GreenterClient;
use Greenter\Model\Company\Address;
use Greenter\Model\Company\Company as GreenterCompany;
use Greenter\Model\Sale\FormaPagos\FormaPagoContado;
use Greenter\Model\Sale\Invoice as GreenterInvoice;
use Greenter\Model\Sale\Legend;
use Greenter\Model\Sale\SaleDetail;

class BoletaFormatter
{
    public function formatBoleta(array $data = []): GreenterInvoice
    {
        $client = new GreenterClient;
        $client->setTipoDoc($data['client']['tipo_doc'] ?? '1')
            ->setNumDoc($data['client']['num_doc'] ?? '00000000')
            ->setRznSocial($data['client']['rzn_social'] ?? 'CLIENTE GENERAL');

        $address = new Address;
        $address->setUbigueo($data['company']['address']['ubigeo'] ?? '150101')
            ->setDepartamento($data['company']['address']['departamento'] ?? 'LIMA')
            ->setProvincia($data['company']['address']['provincia'] ?? 'LIMA')
            ->setDistrito($data['company']['address']['distrito'] ?? 'LIMA')
            ->setDireccion($data['company']['address']['direccion'] ?? 'AV PERU 123');

        $company = new GreenterCompany;
        $company->setRuc($data['company']['ruc'] ?? '20000000001')
            ->setRazonSocial($data['company']['razon_social'] ?? 'EMPRESA DEMO SA')
            ->setNombreComercial($data['company']['nombre_comercial'] ?? 'EMPRESA DEMO')
            ->setAddress($address);

        $details = [];
        $detailsData = $data['details'] ?? [
            [
                'cod_producto' => 'P001',
                'unidad' => 'NIU',
                'cantidad' => 1,
                'mto_valor_unitario' => 100.00,
                'descripcion' => 'PRODUCTO 1',
                'mto_base_igv' => 100.00,
                'porcentaje_igv' => 18.00,
                'igv' => 18.00,
                'tip_afe_igv' => '10',
                'total_impuestos' => 18.00,
                'mto_valor_venta' => 100.00,
                'mto_precio_unitario' => 118.00,
            ],
        ];

        foreach ($detailsData as $item) {
            $detail = new SaleDetail;
            $detail->setCodProducto($item['cod_producto'] ?? 'P001')
                ->setUnidad($item['unidad'] ?? 'NIU')
                ->setCantidad($item['cantidad'] ?? 1)
                ->setMtoValorUnitario($item['mto_valor_unitario'] ?? 100.00)
                ->setDescripcion($item['descripcion'] ?? 'PRODUCTO DEMO')
                ->setMtoBaseIgv($item['mto_base_igv'] ?? 100.00)
                ->setPorcentajeIgv($item['porcentaje_igv'] ?? 18.00)
                ->setIgv($item['igv'] ?? 18.00)
                ->setTipAfeIgv($item['tip_afe_igv'] ?? '10')
                ->setTotalImpuestos($item['total_impuestos'] ?? 18.00)
                ->setMtoValorVenta($item['mto_valor_venta'] ?? 100.00)
                ->setMtoPrecioUnitario($item['mto_precio_unitario'] ?? 118.00);
            $details[] = $detail;
        }

        // Calcular totales
        $mtoOperGravadas = array_sum(array_map(fn ($d) => $d->getMtoBaseIgv(), $details));
        $mtoIGV = array_sum(array_map(fn ($d) => $d->getIgv(), $details));
        $valorVenta = array_sum(array_map(fn ($d) => $d->getMtoValorVenta(), $details));
        $total = $valorVenta + $mtoIGV;

        // Crear boleta
        $invoice = new GreenterInvoice;
        $invoice->setUblVersion('2.1')
            ->setTipoOperacion('0101')
            ->setTipoDoc($data['tipo_doc'] ?? '03')
            ->setSerie($data['serie'] ?? 'B001')
            ->setCorrelativo($data['correlativo'] ?? '00000001')
            ->setFechaEmision($data['fecha_emision'] ?? new \DateTime)
            ->setFormaPago(new FormaPagoContado)
            ->setTipoMoneda($data['tipo_moneda'] ?? 'PEN')
            ->setCompany($company)
            ->setClient($client)
            ->setMtoOperGravadas($mtoOperGravadas)
            ->setMtoIGV($mtoIGV)
            ->setTotalImpuestos($mtoIGV)
            ->setValorVenta($valorVenta)
            ->setSubTotal($total)
            ->setMtoImpVenta($total)
            ->setDetails($details);

        // Agregar leyenda
        $legend = new Legend;
        $legend->setCode('1000')
            ->setValue('SON '.$this->numberToWords($total).' SOLES');
        $invoice->setLegends([$legend]);

        return $invoice;
    }

    private function numberToWords(float $number): string
    {
        return number_format($number, 2).' CON 00/100';
    }
}
