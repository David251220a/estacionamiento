<?php

namespace App\Http\Livewire\Plan;

use App\Models\PlanPersona;
use Carbon\Carbon;
use Livewire\Component;

class PlanEditar extends Component
{

    public $plan_persona, $persona, $estado_id, $fecha_fin, $procesando;

    public function mount(PlanPersona $plan_persona)
    {
        $this->plan_persona = $plan_persona;
        $this->persona = $plan_persona->persona;
        $this->estado_id = $plan_persona->estado_id;
        $this->fecha_fin = $plan_persona->fecha_fin;
        $this->procesando = false;
    }

    public function render()
    {
        return view('livewire.plan.plan-editar');
    }

    public function calcular()
    {
        $fecha_desde = Carbon::parse($this->plan_persona->fecha_inicio);
        $fechaHasta = null;
        switch ($this->plan_persona->plan_id) {
            case 2:
                $fechaHasta = $fecha_desde->copy()->addDays($this->plan_persona->cantidad);
                break;
            case 3:
                $fechaHasta = $fecha_desde->copy()->addWeeks($this->plan_persona->cantidad);
                break;
            case 4:
                $fechaHasta = $fecha_desde->copy()->addMonths($this->plan_persona->cantidad);
                break;
            case 5:
                $fechaHasta = $fecha_desde->copy()->addYears($this->plan_persona->cantidad);
                break;
        }

        $this->fecha_fin = $fechaHasta->format('Y-m-d');
        $this->emit('mensaje_exitoso', 'Fecha calculado correctamente.');
    }

    public function guardar()
    {
        $plan = PlanPersona::find($this->plan_persona->id);
        $plan->update([
            'fecha_fin' => $this->fecha_fin,
            'estado_id' => $this->estado_id,
        ]);

        return redirect()->route('planpersona.index')->with('message', 'Actualizado correctamente.');
    }


}
