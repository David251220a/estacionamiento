<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\RegistroDiario;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Picqer\Barcode\BarcodeGeneratorPNG;

class RegistroDiarioController extends Controller
{
    public function index()
    {
        return view('registro.index');
    }

    public function create()
    {
        return view('registro.create');
    }

    public function show(RegistroDiario $registro_diario)
    {
        return view('registro.show', compact('registro_diario'));
    }

    public function imprimir(RegistroDiario $registro_diario)
    {
        $generator = new BarcodeGeneratorPNG();
        $barcode = base64_encode(
            $generator->getBarcode($registro_diario->id, $generator::TYPE_CODE_128)
        );
        $pdf = Pdf::loadView('reportes.ingreso_vehiculo', compact('registro_diario', 'barcode'));
        $pdf->setPaper([0, 0, 226.772, 350.394], 'portrait');

        return $pdf->stream('ingreso_vehiculo.pdf');
    }

    public function pagar_tarifa(RegistroDiario $registro_diario)
    {
        if($registro_diario->facturado == 1){
            return redirect()->back()->withErrors('Este registro ya esta facturado.');
        }
        return view('registro.pagar', compact('registro_diario'));
    }

}
