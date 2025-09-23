<?php

namespace App\Services\Invoices\Formatters;

use Greenter\Model\Sale\Invoice as GreenterInvoice;
use Greenter\Model\Sale\SaleDetail;
use Greenter\Model\Sale\Legend;
use Greenter\Model\Client\Client as GreenterClient;
use Greenter\Model\Company\Company as GreenterCompany;
use Greenter\Model\Company\Address;
use Greenter\Model\Sale\FormaPagos\FormaPagoContado;


class FacturaFormatter
{
    public function formatFactura(array $data = []): GreenterInvoice
    {
        // Valores estáticos por defecto, pueden ser sobrescritos por $data
        $defaults = [
            'tipo_doc' => '01', // Factura
            'serie' => 'F001',
            'correlativo' => '00000001',
            'fecha_emision' => new \DateTime(),
            'tipo_moneda' => 'PEN',
            'mto_oper_gravadas' => 100.00,
            'mto_igv' => 18.00,
            'total_impuestos' => 18.00,
            'valor_venta' => 100.00,
            'sub_total' => 100.00,
            'mto_imp_venta' => 118.00,
            'client' => [
                'tipo_doc' => '6', // RUC por defecto
                'num_doc' => '12345678901',
                'rzn_social' => 'Cliente Ejemplo',
            ],
            'company' => [
                'ruc' => '12345678901',
                'razon_social' => 'Empresa Ejemplo',
                'nombre_comercial' => 'Empresa Ejemplo',
                'ubigeo' => '150101',
                'direccion' => 'Dirección Ejemplo',
            ],
            'details' => [
                [
                    'cod_producto' => 'P001',
                    'unidad' => 'NIU',
                    'cantidad' => 1,
                    'mto_valor_unitario' => 100.00,
                    'descripcion' => 'Producto Ejemplo',
                    'mto_base_igv' => 100.00,
                    'porcentaje_igv' => 18.00,
                    'igv' => 18.00,
                    'tip_afe_igv' => '10',
                    'total_impuestos' => 18.00,
                    'mto_valor_venta' => 100.00,
                    'mto_precio_unitario' => 118.00,
                ],
            ],
        ];

        $config = array_merge($defaults, $data);

        $greenterClient = (new GreenterClient())
            ->setTipoDoc($config['client']['tipo_doc'])
            ->setNumDoc($config['client']['num_doc'])
            ->setRznSocial($config['client']['rzn_social']);

        $address = (new Address())
            ->setUbigueo($config['company']['ubigeo'])
            ->setDireccion($config['company']['direccion']);

        $greenterCompany = (new GreenterCompany())
            ->setRuc($config['company']['ruc'])
            ->setRazonSocial($config['company']['razon_social'])
            ->setNombreComercial($config['company']['nombre_comercial'])
            ->setAddress($address);

        $invoice = (new GreenterInvoice())
            ->setUblVersion('2.1')
            ->setTipoOperacion('0101')
            ->setTipoDoc($config['tipo_doc'])
            ->setSerie($config['serie'])
            ->setCorrelativo($config['correlativo'])
            ->setFechaEmision($config['fecha_emision'])
            ->setFormaPago(new FormaPagoContado())
            ->setTipoMoneda($config['tipo_moneda'])
            ->setCompany($greenterCompany)
            ->setClient($greenterClient)
            ->setMtoOperGravadas($config['mto_oper_gravadas'])
            ->setMtoIGV($config['mto_igv'])
            ->setTotalImpuestos($config['total_impuestos'])
            ->setValorVenta($config['valor_venta'])
            ->setSubTotal($config['sub_total'])
            ->setMtoImpVenta($config['mto_imp_venta']);

        $details = [];
        foreach ($config['details'] as $detailData) {
            $detail = (new SaleDetail())
                ->setCodProducto($detailData['cod_producto'])
                ->setUnidad($detailData['unidad'])
                ->setCantidad($detailData['cantidad'])
                ->setMtoValorUnitario($detailData['mto_valor_unitario'])
                ->setDescripcion($detailData['descripcion'])
                ->setMtoBaseIgv($detailData['mto_base_igv'])
                ->setPorcentajeIgv($detailData['porcentaje_igv'])
                ->setIgv($detailData['igv'])
                ->setTipAfeIgv($detailData['tip_afe_igv'])
                ->setTotalImpuestos($detailData['total_impuestos'])
                ->setMtoValorVenta($detailData['mto_valor_venta'])
                ->setMtoPrecioUnitario($detailData['mto_precio_unitario']);
            $details[] = $detail;
        }

        $legend = (new Legend())
            ->setCode('1000')
            ->setValue('SON ' . strtoupper(\NumberFormatter::create('es_PE', \NumberFormatter::SPELLOUT)->format($config['mto_imp_venta'])) . ' SOLES');

        $invoice->setDetails($details)->setLegends([$legend]);
        return $invoice;
    }
}
