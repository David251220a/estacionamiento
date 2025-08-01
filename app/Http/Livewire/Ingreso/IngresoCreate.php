<?php

namespace App\Http\Livewire\Ingreso;

use App\Models\Color;
use App\Models\Marca;
use App\Models\Modelo;
use App\Models\Persona;
use App\Models\Plan;
use App\Models\RegistroDiario;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class IngresoCreate extends Component
{
    public $colores, $modelos, $marcas, $planes;
    public $plan_id_registrar, $hora_ingreso, $marca_id_registrar, $modelo_id_registrar, $color_id_registrar
    , $chapa_registrar, $procesando, $registro_diario;

    public function mount()
    {
        $this->planes = Plan::all();
        $this->plan_id_registrar = $this->planes[0]->id;
        $this->colores = Color::all();
        $this->color_id_registrar = $this->colores[0]->id;
        $this->marcas = Marca::all();
        $this->marca_id_registrar = $this->marcas[0]->id;
        $this->modelos = Modelo::where('marca_id', $this->marca_id_registrar)
        ->get();
        $this->modelo_id_registrar = $this->modelos[0]->id;
        $this->hora_ingreso = Carbon::now()->format('H:i');
        $this->procesando = false;
    }

    public function render()
    {
        return view('livewire.ingreso.ingreso-create');
    }

    public function guardar()
    {
        $this->procesando = true;

        try {

            DB::transaction(function () {

                $fecha = Carbon::now()->toDateString();
                $hora_ingreso = Carbon::now()->format('H:i');
                $anio = now()->year;

                // Obtener siguiente ticket para el año
                $ultimoTicket = RegistroDiario::where('anio', $anio)
                ->lockForUpdate()
                ->max('ticket');

                $ticket = $ultimoTicket ? $ultimoTicket + 1 : 1;
                $persona = Persona::find(1);

                $this->registro_diario = RegistroDiario::create([
                    'persona_id' => $persona->id,
                    'plan_id' => $this->plan_id_registrar,
                    'marca_id' => $this->marca_id_registrar,
                    'modelo_id' => $this->modelo_id_registrar,
                    'color_id' => $this->color_id_registrar,
                    'tipo_vehiculo_id' => 1,
                    'chapa' => $this->chapa_registrar,
                    'fecha' => $fecha,
                    'hora_ingreso' => $hora_ingreso,
                    'hora_salida' => null,
                    'plan_persona' => 0,
                    'plan_activo' => 0,
                    'facturado' => 0,
                    'anio' => $anio,
                    'ticket' => $ticket,
                    'estado_id' => 1,
                    'user_id' => auth()->user()->id,
                ]);
            });

            return redirect()->route('registro.show', $this->registro_diario)->with('message', 'Registro creado con éxito');

        } catch (\Exception $e) {
            $this->emit('mensaje_error', $e->getMessage());
            $this->procesando = false;
        }

    }
}
