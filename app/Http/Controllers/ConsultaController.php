<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use App\Models\FormaCobro;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ConsultaController extends Controller
{
    public function pagos()
    {
        return view('consulta.pagos');
    }

    public function pagos_imprimir($fechaDesde, $fechaHasta, $formaCobro)
    {

        if($formaCobro == 99){
            $forma_cobro_desde = 0;
            $forma_cobro_hasta = 99;
        } else {
            $forma_cobro_desde = $formaCobro;
            $forma_cobro_hasta = $formaCobro;
        }

        $data = Factura::where('estado_id', 1)
        ->whereBetween('fecha_factura', [$fechaDesde, $fechaHasta])
        ->whereHas('facturaPago', function ($query) use ($forma_cobro_desde, $forma_cobro_hasta) {
            $query->whereBetween('forma_cobro_id', [$forma_cobro_desde, $forma_cobro_hasta]);
        })
        ->get();

        $titulo_cobro = '';
        if($formaCobro == 99){
            $titulo_cobro = 'TODOS';
        } else {
            $cobro = FormaCobro::find($formaCobro);
            $titulo_cobro = $cobro->descripcion;
        }

        $pdf = Pdf::loadView('reportes.pagos_ingresos', compact('data', 'fechaDesde', 'fechaHasta', 'titulo_cobro'))
        ->setPaper('a4', 'landscape');

        return $pdf->stream('pagos_imprimir.pdf');
    }
}
