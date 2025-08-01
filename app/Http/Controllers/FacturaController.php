<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use Barryvdh\DomPDF\Facade\Pdf;
use Picqer\Barcode\BarcodeGeneratorPNG;

class FacturaController extends Controller
{

    public function show(Factura $factura)
    {
        $registro_diario = $factura->registroDiario;
        return view('sifen.cobrado', compact('registro_diario', 'factura'));
    }

    public function imprimir(Factura $factura)
    {
        $generator = new BarcodeGeneratorPNG();
        $registro_diario = $factura->registroDiario;
        $barcode = base64_encode(
            $generator->getBarcode($registro_diario->id, $generator::TYPE_CODE_128)
        );
        $pdf = Pdf::loadView('reportes.factura', compact('registro_diario', 'barcode', 'factura'));
        $pdf->setPaper([0, 0, 226.772, 420.394], 'portrait');

        return $pdf->stream('factura_estacionamiento.pdf');
    }
}
