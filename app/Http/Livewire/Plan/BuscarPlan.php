<?php

namespace App\Http\Livewire\Plan;

use App\Models\Persona;
use App\Models\PlanPersona;
use App\Models\RegistroDiario;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class BuscarPlan extends Component
{

    public $ver_buscar, $registro_diario, $documento, $persona, $personaplan, $verdatos
    , $nombre, $planActivo, $fechaDesde, $fechaHasta, $editar_documento, $procesando;

    public function mount(RegistroDiario $registro_diario)
    {
        $this->registro_diario = $registro_diario;
        $this->ver_buscar = 'block';
        $this->verdatos = 'none';
        $this->personaplan = [];
        $this->persona = [];
        $this->editar_documento = true;
        $this->procesando = false;
    }

    public function render()
    {
        return view('livewire.plan.buscar-plan');
    }

    public function buscar_persona()
    {
        if(empty($this->documento)){
            $this->emit('mensaje_error', 'El numero de documento no puede ser vacio');
            return false;
        }

        $documento = str_replace('.', '', $this->documento);
        if (!is_numeric($documento) || $documento <= 0) {
            $this->emit('mensaje_error', 'El numero de documento debe ser mayor a cero.');
            return false;
        }

        $this->persona = Persona::where('documento', $documento)
        ->first();

        if ($this->persona){
            $hoy = Carbon::now()->format('Y-m-d');
            $this->actualizarPlanesVencidosDePersona();
            $this->personaplan = PlanPersona::where('estado_id', 1)
            ->where('persona_id', $this->persona->id)
            ->where('fecha_inicio', '<=', $hoy)
            ->where('fecha_fin', '>=', $hoy)
            ->first();

            if ($this->personaplan){
                $this->verdatos = 'block';
                $this->ver_buscar = 'none';
                $this->nombre = $this->persona->nombre . ' ' . $this->persona->apellido;
                $this->planActivo = $this->personaplan->plan->descripcion;
                $this->fechaDesde = $this->personaplan->fecha_inicio;
                $this->fechaHasta = $this->personaplan->fecha_fin;
                $this->editar_documento = false;
            }else{
                $this->emit('mensaje_error', 'La persona no cuenta con un plan activo.');
                $this->personaplan = [];
                $this->persona = [];
                $this->verdatos = 'none';
                $this->ver_buscar = 'block';
                $this->editar_documento = true;
                return false;
            }
        } else {
            $this->emit('mensaje_error', 'No existe persona con este numero de documento.');
            $this->personaplan = [];
            $this->persona = [];
            $this->verdatos = 'none';
            $this->ver_buscar = 'block';
            $this->editar_documento = true;
            return false;
        }
    }

    public function cancelar()
    {
        $this->verdatos = 'none';
        $this->ver_buscar = 'block';
        $this->nombre = '';
        $this->planActivo = '';
        $this->fechaDesde = null;
        $this->fechaHasta = null;
        $this->editar_documento = true;
    }

    public function guardar()
    {
        $this->procesando = true;
        try {
            DB::transaction(function () {
                $registro = RegistroDiario::find($this->registro_diario->id);
                $registro->update([
                    'persona_id' => $this->persona->id,
                    'plan_id' => $this->personaplan->plan_id,
                    'plan_activo' => 1,
                    'facturado' => 1,
                    'plan_persona' => $this->personaplan->id,
                ]);
            });
            return redirect()->route('registro.index')->with('message', 'Aplicado el plan correctamente.');
        } catch (\Throwable $e) {
            $this->emit('mensaje_error', 'Ocurrió un error al generar la factura: ' . $e->getMessage());
            $this->procesando = false;
            return false;
        }
    }

    public function actualizarPlanesVencidosDePersona()
    {
        $hoy = Carbon::now()->format('Y-m-d');

        PlanPersona::where('estado_id', 1)
        ->where('persona_id', $this->persona->id)
        ->where('fecha_fin', '<', $hoy)
        ->update(['estado_id' => 2]);
    }

}
