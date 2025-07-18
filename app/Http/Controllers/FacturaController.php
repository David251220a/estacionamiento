<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use App\Models\Sifen;
use App\Services\FacturaJsonBuilder;
use App\Services\FacturaXMLBuilder;
use Illuminate\Http\Request;

class FacturaController extends Controller
{

    public function show(Factura $factura)
    {
        $sifen = Sifen::where('factura_id', $factura->id)
        ->first();
        if (!($sifen)){
            $builder = new FacturaJsonBuilder($factura);
            $xml = new FacturaXMLBuilder();
            $json = [];
            if($factura->tipo_documento_id == 1){
                $json = $builder->jsonContado();
            }
            $documento =  $xml->generate($json, $factura->timbrado_id);
            $sifen = Sifen::create([
                'factura_id' => $factura->id,
                'cdc' => $documento['cdc'],
                'tipo_doc' => $factura->tipo_documento_id,
                'documento_xml' => $documento['archivo_xml'],
                'documento_pdf' => 'facturas/' . $documento['cdc'] .'.pdf',
                'zipeado' => 'N',
                'secuencia' => 0,
                'sifen_num_transaccion' => 0,
                'sifen_estado' => 'PENDIENTE',
                'sifen_mensaje' => ' ',
                'fecha_firma' => $documento['fecha_firma'],
                'link_qr' => $documento['link_qr'],
                'evento' => null,
                'sifen_cod' => 0,
                'tipo_transaccion' => $factura->tipo_transaccion_id,
                'condicion_pago' => $factura->condicion_pago,
                'moneda' => 'PYG',
                'correo_enviado' => 'N',
            ]);
        }

        return view('factura.show', compact('factura', 'sifen'));
    }
}
