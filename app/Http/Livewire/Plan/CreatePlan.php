<?php

namespace App\Http\Livewire\Plan;

use App\Models\Color;
use App\Models\Marca;
use App\Models\Modelo;
use App\Models\Persona;
use App\Models\Plan;
use App\Models\PlanPersona;
use App\Models\Sexo;
use App\Models\Vehiculo;
use Carbon\Carbon;
use Hamcrest\Type\IsNumeric;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreatePlan extends Component
{

    public $sexo, $documento, $nombre, $apellido, $ruc, $email
    , $fecha_nac, $sexo_id, $celular, $estado_civil_id, $persona
    , $marcas, $marca_id, $modelos, $modelo_id, $chapa_registrar
    , $colores, $color_id, $planes, $plan_id, $cantidad, $fecha_desde, $fecha_hasta
    , $procesando;

    public function mount()
    {
        $this->sexo = Sexo::all();
        $this->sexo_id = 1;
        $this->estado_civil_id = 0;
        $this->marcas = Marca::all();
        $this->marca_id = 1;
        $this->modelos = Modelo::where('marca_id', $this->marca_id)
        ->get();
        $this->modelo_id = $this->modelos[0]->id;
        $this->colores = Color::all();
        $this->color_id = 1;
        $this->planes = Plan::whereIn('id', [3, 4, 5])
        ->get();
        $this->plan_id = 3;
        $this->cantidad = 1;
        $this->fecha_desde = null;
        $this->estableceRango();
        $this->procesando = false;
    }

    public function updatedPlanId($value)
    {
        $this->plan_id = $value;
        $this->estableceRango();
    }

    public function validarCantidad()
    {
        $value = $this->cantidad;
        if (!is_numeric($value) || $value <= 0) {
            $this->emit('mensaje_error', 'La cantidad debe ser un número válido mayor a cero.');
            $this->cantidad = 1; // valor por defecto si querés
            $value = 1;
            return false;
        }
        $this->estableceRango();

    }

    public function render()
    {
        return view('livewire.plan.create-plan');
    }

    public function verificarDocumento()
    {
        $documento = str_replace('.', '', $this->documento);
        $persona = Persona::where('documento', $documento)
        ->first();

        if($persona){
            $this->persona = $persona;
            $this->nombre = $this->persona->nombre;
            $this->apellido = $this->persona->apellido;
            $this->ruc = $this->persona->ruc;
            $this->email = $this->persona->email;
            $this->fecha_nac = $persona->fecha_nacimiento;
            $this->sexo_id = $persona->sexo_id;
            $this->estado_civil_id = $persona->estado_civil;
            $this->celular = $persona->celular;
            if ($persona->vehiculo_ultimo_registro){
                $this->marca_id = $persona->vehiculo_ultimo_registro->marca_id;
                $this->modelos = Modelo::where('marca_id', $this->marca_id)
                ->get();
                $this->modelo_id = $this->modelos[0]->id;
                $this->chapa_registrar = $persona->vehiculo_ultimo_registro->chapa;
            }else {
                $this->modelos = Modelo::where('marca_id', $this->marca_id)
                ->get();
                $this->modelo_id = $this->modelos[0]->id;
            }
        } else {
            $this->persona = null;
            $this->nombre = '';
            $this->apellido = '';
            $this->ruc = '';
            $this->email = '';
            $this->fecha_nac = null;
            $this->sexo_id = 1;
            $this->estado_civil_id = 0;
            $this->celular = '';
            $this->marca_id = 1;
            $this->modelos = Modelo::where('marca_id', $this->marca_id)
            ->get();
            $this->modelo_id = $this->modelos[0]->id;
        }
    }

    public function estableceRango()
    {
        if (empty($this->fecha_desde)){
            $this->fecha_desde = Carbon::now()->format('Y-m-d');
        }

        $fecha_desde = Carbon::parse($this->fecha_desde);
        $fechaHasta = null;
        switch ($this->plan_id) {
            case 2:
                $fechaHasta = $fecha_desde->copy()->addDays($this->cantidad);
                break;
            case 3:
                $fechaHasta = $fecha_desde->copy()->addWeeks($this->cantidad);
                break;
            case 4:
                $fechaHasta = $fecha_desde->copy()->addMonths($this->cantidad);
                break;
            case 5:
                $fechaHasta = $fecha_desde->copy()->addYears($this->cantidad);
                break;
        }
        // switch ($this->plan_id) {
        //     case 2:
        //         $fechaHasta = $this->calcularRangoHabiles($fecha_desde, $this->cantidad);
        //         break;
        //     case 3:
        //         $fechaHasta = $this->calcularRangoHabiles($fecha_desde, ($this->cantidad * 7));
        //         break;
        //     case 4:
        //         $fechaHasta = $this->calcularRangoHabiles($fecha_desde, ($this->cantidad * 30));
        //         break;
        //     case 5:
        //         $fechaHasta = $this->calcularRangoHabiles($fecha_desde, ($this->cantidad * 365));
        //         break;
        // }

        $this->fecha_hasta = $fechaHasta->format('Y-m-d');
    }

    public function validarFechaDesde()
    {
        $this->estableceRango();
    }

    public function calcularRangoHabiles($fechaInicio, $totalDias)
    {
        $diasHabiles = 0;
        $fecha = $fechaInicio->copy();
        while ($diasHabiles < $totalDias) {
            $fecha->addDay();
            if (!$fecha->isWeekend()) {
                $diasHabiles++;
            }
        }
        return $fecha;
    }

    public function guardar()
    {
        $this->procesando = true;

        if (empty($this->documento)){
            $this->emit('mensaje_error', 'El numero de documento no puede ser vacio.');
            return false;
        }
        $documento = str_replace('.', '', $this->documento);
        if (!is_numeric($documento) || $documento <= 0) {
            $this->emit('mensaje_error', 'El numero de documento debe ser mayor a cero.');
            return false;
        }

        if (empty($this->nombre)){
            $this->emit('mensaje_error', 'El nombre de la persona no puede ser vacio.');
            return false;
        }

        if (empty($this->apellido)){
            $this->emit('mensaje_error', 'El nombre de la persona no puede ser vacio.');
            return false;
        }

        if (!is_numeric($this->cantidad) || $this->cantidad <= 0) {
            $this->emit('mensaje_error', 'La cantidad debe ser mayor a cero.');
            return false;
        }

        if (empty($this->email)){
            $this->emit('mensaje_error', 'El correo electronico no puede ser vacio.');
            return false;
        }

        if ($this->persona){
            $hoy = Carbon::now()->format('Y-m-d');

            $existePlan = PlanPersona::where('estado_id', 1)
            ->where('persona_id', $this->persona->id)
            ->where('fecha_inicio', '<=', $hoy)
            ->where('fecha_fin', '>=', $hoy)
            ->exists();

            if ($existePlan){
                $this->emit('mensaje_error', 'La persona tiene un plan activo.');
                return false;
            }
        }

        try {
            DB::transaction(function () {
                if($this->persona == null){
                    $documento = str_replace('.', '', $this->documento);
                    $persona = Persona::create([
                        'documento' => $documento,
                        'nombre' => $this->nombre,
                        'apellido' => $this->apellido,
                        'fecha_nacimiento' => null,
                        'sexo_id' => $this->sexo_id,
                        'estado_civil' => 0,
                        'email' => $this->email,
                        'celular' => '',
                        'ruc' => $this->ruc,
                        'estado_id' => 1,
                        'user_id' => 1,
                        'usuario_modificacion' => 1
                    ]);
                    $this->persona = $persona;
                }

                $vehiculo = Vehiculo::updateOrCreate(
                    [
                        'chapa' => $this->chapa_registrar,
                        'persona_id' => $this->persona->id,
                    ],
                    [
                        'marca_id' => $this->marca_id,
                        'modelo_id' => $this->modelo_id,
                        'color_id' => $this->color_id,
                        'tipo_vehiculo_id' => 1,
                        'estado_id' => 1,
                        'user_id' => auth()->user()->id,
                    ]
                );

                $plan_persona = PlanPersona::create([
                    'persona_id' => $this->persona->id,
                    'plan_id' => $this->plan_id,
                    'fecha_inicio' => $this->fecha_desde,
                    'fecha_fin' => $this->fecha_hasta,
                    'cantidad' => $this->cantidad,
                    'estado_id' => 1,
                    'user_id' => auth()->user()->id,
                ]);
            });

            return redirect()->route('registro.index')->with('message', 'Plan creado con exito');
        } catch (\Throwable $e) {
            $this->emit('mensaje_error', 'Ocurrió un error al generar la factura: ' . $e->getMessage());
            $this->procesando = false;
            return false;
        }

    }


}
