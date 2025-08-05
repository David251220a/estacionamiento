<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\PlanPersona;
use App\Models\RegistroDiario;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Picqer\Barcode\BarcodeGeneratorPNG;

class PlanPersonaController extends Controller
{

    public function index()
    {
        return view('plan.index');
    }

    public function create()
    {
        return view('plan.create');
    }

    public function show(RegistroDiario $registro_diario)
    {
        return view('plan.show', compact('registro_diario'));
    }

    public function imprimir(RegistroDiario $registro_diario)
    {
        $generator = new BarcodeGeneratorPNG();
        $barcode = base64_encode(
            $generator->getBarcode($registro_diario->id, $generator::TYPE_CODE_128)
        );
        $PersonaPlan = PlanPersona::find($registro_diario->plan_persona);
        $plan = Plan::find($registro_diario->plan_id);
        $pdf = Pdf::loadView('reportes.pago_con_plan', compact('registro_diario', 'barcode', 'PersonaPlan', 'plan'));
        $pdf->setPaper([0, 0, 226.772, 350.394], 'portrait');

        return $pdf->stream('factura_con_plan.pdf');
    }

    public function edit(PlanPersona $plan_persona)
    {
        return view('plan.editar', compact('plan_persona'));
    }

}
