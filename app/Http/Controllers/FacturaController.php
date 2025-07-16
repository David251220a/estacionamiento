<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use App\Services\FacturaJsonBuilder;
use Illuminate\Http\Request;

class FacturaController extends Controller
{
    public function show(Factura $factura)
    {

        $builder = new FacturaJsonBuilder($factura);
        $json = $builder->build();
        return $json;

        return view('factura.show', compact('factura'));
    }
}
