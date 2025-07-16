<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use App\Services\FacturaJsonBuilder;
use App\Services\FacturaXMLBuilder;
use Illuminate\Http\Request;

class FacturaController extends Controller
{
    public function show(Factura $factura)
    {

        $builder = new FacturaJsonBuilder($factura);
        $xml = new FacturaXMLBuilder();
        $json = $builder->build();
        //return $json;
        $nombre_archivo =  $xml->generate($json, $factura->timbrado_id);
        
        return $nombre_archivo;

        return view('factura.show', compact('factura'));
    }
}
