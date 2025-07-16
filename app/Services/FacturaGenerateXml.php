<?php

namespace App\Services;

use App\Models\Entidad;
use DOMDocument;
use Illuminate\Support\Facades\Storage;

class FacturaXMLBuilder
{

    protected $entidad;

    public function __construct()
    {
        $this->entidad = Entidad::find(1); // o recibir por constructor si necesitas flexibilidad
    }
    
    public function generate(array $json): string
    {
        // Aquí vamos a construir el XML paso a paso desde $json
        $xml = new DOMDocument('1.0', 'UTF-8');
        $xml->formatOutput = true;

        // 1. Nodo raíz
        $root = $xml->createElement('DE'); // Documento Electrónico
        $xml->appendChild($root);

        // 2. Agregar estructura básica (ejemplo para fecha y número)
        $root->appendChild($xml->createElement('dFeEmiDE', $json['fecha']));
        $root->appendChild($xml->createElement('dNumDoc', $json['numero']));

        // 2.1 Agregar nodo de datos del receptor (gDatRec)
        $gDatRec = $xml->createElement('gDatRec');

        $cliente = $json['cliente'];
        $gDatRec->appendChild($xml->createElement('iNatRec', 1)); // 1 = Nacional
        $gDatRec->appendChild($xml->createElement('iTiOpe', $json['tipoTransaccion']));
        $gDatRec->appendChild($xml->createElement('cPaisRec', $cliente['cpais'])); // PRY
        $gDatRec->appendChild($xml->createElement('ruc', $cliente['ruc'] ?: '')); // puede estar vacío si no tiene
        $gDatRec->appendChild($xml->createElement('dNomRec', $cliente['nombre']));
        $gDatRec->appendChild($xml->createElement('dDirRec', $cliente['direccion']));
        $gDatRec->appendChild($xml->createElement('dEmailRec', $cliente['correo']));
        $gDatRec->appendChild($xml->createElement('cNumCas', $cliente['numCasa']));
        $gDatRec->appendChild($xml->createElement('iTiCont', $json['tipoContribuyente']));
        $tipoIdRec = (!empty($json['cliente']['ruc'])) ? 2 : 1;
        $gDatRec->appendChild($xml->createElement('iTipIDRec', $tipoIdRec));

        $root->appendChild($gDatRec);

        // 2.2 Agregar nodo de ítems (gCamItem)
        foreach ($json['items'] as $item) {
            $gCamItem = $xml->createElement('gCamItem');

            $gCamItem->appendChild($xml->createElement('dDesProSer', $item['descripcion']));
            $gCamItem->appendChild($xml->createElement('cProSer', $item['codigo']));
            $gCamItem->appendChild($xml->createElement('dCantProSer', $item['cantidad']));
            $gCamItem->appendChild($xml->createElement('cUniMed', $item['unidadMedida']));
            $gCamItem->appendChild($xml->createElement('dPreUni', $item['precioUnitario']));
            $gCamItem->appendChild($xml->createElement('dTotBruOpeItem', $item['precioTotal']));
            $gCamItem->appendChild($xml->createElement('dTotOpeItem', $item['baseGravItem']));
            $gCamItem->appendChild($xml->createElement('dDesItem', 0)); // Descuento, si no usás poné 0
            $gCamItem->appendChild($xml->createElement('dLiqIVAItem', $item['liqIvaItem']));
            $gCamItem->appendChild($xml->createElement('iAfecIVA', $item['ivaAfecta']));
            $gCamItem->appendChild($xml->createElement('dPropIVA', $item['ivaTasa']));

            $root->appendChild($gCamItem);
        }

        // 2.3 Agregar nodo de pagos (gPaConEIni)
        $gPaConEIni = $xml->createElement('gPaConEIni');

        // Total general pagado
        $gPaConEIni->appendChild($xml->createElement('dTotTPag', $json['totalPago']));

        // Por cada forma de pago
        foreach ($json['pagos'] as $pago) {
            $gPaConEIniSub = $xml->createElement('gPagEIni');

            $gPaConEIniSub->appendChild($xml->createElement('iTiPago', $pago['tipoPago']));
            $gPaConEIniSub->appendChild($xml->createElement('dMonTiPag', $pago['monto']));

            // Si es cheque (2) o transferencia (3), incluir banco
            if (in_array($pago['tipoPago'], ['2', '3']) && isset($pago['descripcion_pago'])) {
                $gPaConEIniSub->appendChild($xml->createElement('dBcoEmi', $pago['descripcion_pago']));
            }

            $gPaConEIni->appendChild($gPaConEIniSub);
        }

        $root->appendChild($gPaConEIni);

        // TODO: Agregar nodos hijos como gDatRec, gCamItem, pagos, etc.

        // 3. Guardar temporal
        $nombreArchivo = 'xml/' . $json['cdc'] . '.xml';
        Storage::disk('public')->put($nombreArchivo, $xml->saveXML());

        return $nombreArchivo;
    }

}
