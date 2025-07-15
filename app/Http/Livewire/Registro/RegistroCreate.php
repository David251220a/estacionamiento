<?php

namespace App\Http\Livewire\Registro;

use App\Models\Color;
use App\Models\Marca;
use App\Models\Modelo;
use App\Models\Persona;
use App\Models\Plan;
use App\Models\RegistroDiario;
use App\Models\Sexo;
use App\Models\Vehiculo;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class RegistroCreate extends Component
{
    public $planes, $tipo_vehiculos, $marcas, $modelos, $colores, $persona, $sexo, $titulo;
    public $documento_search;
    public $documento_crear, $nombre_crear, $apellido_crear, $ruc_crear, $email_crear, $fecha_nac_crear
    , $sexo_crear, $celular_crear, $estado_civil_crear;
    public $ver_documento_search, $ver_crear_persona, $ver_registrar;
    public $hora_ingreso, $persona_registrar, $plan_id_registrar, $modelo_id_registrar, $marca_id_registrar
    , $color_id_registrar, $chapa_registrar;

    public $procesando = false;


    public function mount()
    {
        $this->titulo = 'Buscar Persona';
        $this->ver_documento_search= 'block';
        $this->ver_crear_persona = 'none';
        $this->ver_registrar = 'none';
        $this->sexo = Sexo::all();
        $this->planes = Plan::all();
        $this->colores = Color::all();
        $this->marcas = Marca::all();
        $this->marca_id_registrar = $this->marcas[0]->id;
        $this->modelos = Modelo::where('marca_id', $this->marca_id_registrar)
        ->get();
    }

    public function render()
    {
        $this->modelos = Modelo::where('marca_id', $this->marca_id_registrar)
        ->get();
        $this->modelo_id_registrar = $this->modelos[0]->id;

        return view('livewire.registro.registro-create');
    }

    public function buscar_persona()
    {
        if(empty($this->documento_search))
        {
            $this->emit('mensaje_error', 'El numero de documento no puede ser vacio.');
            $this->ver_documento_search= 'block';
            return false;
        }

        $documento = str_replace('.', '', $this->documento_search);

        if($this->documento_search == 0){
            $this->emit('mensaje_error', 'El numero de documento no puede ser cero.');
            $this->ver_documento_search= 'block';
            return false;
        }

        $persona = Persona::where('documento', $documento)
        ->first();

        if($persona){
            if($persona->tiene_plan_activo){
                $this->emit('mensaje_error', 'Tiene Activo.');
                return false;
            }else{
                $this->persona = $persona;
                $this->persona_registrar = $persona->documento . '-' . $persona->nombre . ' ' . $persona->apellido;
                $this->plan_id_registrar = 1;
                $this->hora_ingreso = Carbon::now()->format('H:i');
                $this->ver_documento_search = 'none';
                $this->ver_crear_persona = 'none';
                $this->ver_registrar = 'block';
                if ($persona->vehiculo_ultimo_registro){
                    $this->marca_id_registrar = $persona->vehiculo_ultimo_registro->marca_id;
                    $this->modelos = Modelo::where('marca_id', $this->marca_id_registrar)
                    ->get();
                    $this->modelo_id_registrar = $persona->vehiculo_ultimo_registro->modelo_id;
                    $this->color_id_registrar = $persona->vehiculo_ultimo_registro->color_id;
                    $this->chapa_registrar = $persona->vehiculo_ultimo_registro->chapa;
                }
                $this->titulo = 'Registro Vehiculo';
                return false;
            }
        }else{
            $this->titulo = 'Crear Persona';
            $this->documento_crear = $this->documento_search;
            $this->ver_crear_persona = 'block';
        }

        $this->ver_documento_search= 'none';
        return true;
    }

    public function crear_persona()
    {
        if(empty($this->nombre_crear)){
            $this->emit('mensaje_error', 'El nombre no puede ser vacio.');
            $this->ver_documento_search= 'block';
            return false;
        }

        if(empty($this->apellido_crear)){
            $this->emit('mensaje_error', 'El apellido no puede ser vacio.');
            $this->ver_documento_search= 'block';
            return false;
        }

        if(empty($this->email_crear)){
            $this->emit('mensaje_error', 'El email no puede ser vacio.');
            $this->ver_documento_search= 'block';
            return false;
        }

        if(empty($this->email_crear)){
            $this->emit('mensaje_error', 'El email no puede ser vacio.');
            $this->ver_documento_search= 'block';
            return false;
        }

        $documento = str_replace('.', '', $this->documento_search);

        $existe = Persona::where('documento', $documento)
        ->exists();

        if (!($existe)){
            $persona = Persona::create([
                'documento' => $documento,
                'nombre' => $this->nombre_crear,
                'apellido' => $this->apellido_crear,
                'fecha_nacimiento' => $this->fecha_nac_crear,
                'sexo_id' => $this->sexo_crear,
                'estado_civil' => $this->estado_civil_crear,
                'email' => $this->email_crear,
                'celular' => $this->celular_crear,
                'ruc' => $this->ruc_crear,
                'estado_id' => 1,
                'user_id' => auth()->user()->id,
                'usuario_modificacion' => auth()->user()->id,
            ]);

            $this->persona = $persona;
            $this->persona_registrar = $persona->documento . '-' . $persona->nombre . ' ' . $persona->apellido;
        }

        $this->plan_id_registrar = 1;
        $this->hora_ingreso = Carbon::now()->format('H:i');
        $this->ver_crear_persona = 'none';
        $this->ver_registrar = 'block';
        $this->titulo = 'Registro Vehiculo';
    }

    public function registrar_diario()
    {
        $this->procesando = true;

        DB::transaction(function () {

            $fecha = Carbon::now()->toDateString();
            $hora_ingreso = Carbon::now()->format('H:i');
            $anio = now()->year;

            // Vehículo
            $vehiculo = Vehiculo::updateOrCreate(
                [
                    'chapa' => $this->chapa_registrar,
                    'persona_id' => $this->persona->id,
                ],
                [
                    'marca_id' => $this->marca_id_registrar,
                    'modelo_id' => $this->modelo_id_registrar,
                    'color_id' => $this->color_id_registrar,
                    'tipo_vehiculo_id' => 1,
                    'estado_id' => 1,
                    'user_id' => auth()->user()->id,
                ]
            );

            // Obtener siguiente ticket para el año
            $ultimoTicket = RegistroDiario::where('anio', $anio)
            ->lockForUpdate()
            ->max('ticket');

            $ticket = $ultimoTicket ? $ultimoTicket + 1 : 1;

            RegistroDiario::create([
                'persona_id' => $this->persona->id,
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

        return redirect()->route('registro.index')->with('message', 'Registro creado con éxito');
    }

}
