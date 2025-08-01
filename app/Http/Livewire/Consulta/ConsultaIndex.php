<?php

namespace App\Http\Livewire\Consulta;

use App\Models\Factura;
use App\Models\FormaCobro;
use App\Models\RegistroDiario;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class ConsultaIndex extends Component
{

    use WithPagination;

    public $formaCobro, $forma_cobro_id, $fecha_hasta, $fecha_desde
    , $forma_cobro_desde, $forma_cobro_hasta, $total;

    protected $paginationTheme = 'bootstrap';

    protected $listeners = ['anular'];

    public function updatingSearch(){
        $this->resetPage();
    }

    public function mount()
    {
        $this->formaCobro = FormaCobro::whereIn('id', [1, 3, 4, 5, 16, 21])
        ->get();
        $this->forma_cobro_id = 99;
        $this->fecha_hasta = Carbon::now()->format('Y-m-d');
        $this->fecha_desde = Carbon::now()->format('Y-m-d');

    }

    public function render()
    {
        $this->establecer_forma_cobro();
        $data = Factura::where('estado_id', 1)
        ->whereBetween('fecha_factura', [$this->fecha_desde, $this->fecha_hasta])
        ->whereHas('facturaPago', function ($query) {
            $query->whereBetween('forma_cobro_id', [$this->forma_cobro_desde, $this->forma_cobro_hasta]);
        })
        ->paginate(20);

        $this->totalizar();
        // $this->resetPage();
        return view('livewire.consulta.consulta-index', compact('data'));
    }

    public function buscar()
    {
        // $this->resetPage();
    }

    public function establecer_forma_cobro()
    {
        if($this->forma_cobro_id == 99){
            $this->forma_cobro_desde = 0;
            $this->forma_cobro_hasta = 99;
        } else {
            $this->forma_cobro_desde = $this->forma_cobro_id;
            $this->forma_cobro_hasta = $this->forma_cobro_id;
        }
    }

    public function totalizar()
    {
        $this->establecer_forma_cobro();
        $this->total = Factura::whereBetween('fecha_factura', [$this->fecha_desde, $this->fecha_hasta])
        ->whereHas('facturaPago', function ($query) {
            $query->whereBetween('forma_cobro_id', [$this->forma_cobro_desde, $this->forma_cobro_hasta]);
        })
        ->sum('monto_total');
    }

    public function anular($id)
    {

        $factura = Factura::find($id);
        $fecha = Carbon::now()->toDateString();
        $fecha_anulacion = Carbon::now()->format('Y-m-d');
        $factura->update([
            'estado_id' => 2,
            'usuario_anulacion' => auth()->user()->id,
            'motivo_anulacion' => 'Se anula en fecha: ' .$fecha,
            'fecha_anulado' => $fecha_anulacion
        ]);

        $registroDiario = RegistroDiario::find($factura->registro_diario_id);
        $registroDiario->update([
            'facturado' => 0,
        ]);

        $this->emit('correcto', 'Anulado con exito.');
    }

}
