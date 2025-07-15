<?php

namespace App\Http\Livewire\Registro;

use App\Models\Persona;
use App\Models\Sexo;
use Livewire\Component;

class RegistroCreate extends Component
{
    public $planes, $tipo_vehiculos, $marcas, $modelos, $colores, $persona, $sexo, $titulo;
    public $documento_search;
    public $documento_crear, $nombre_crear, $apellido_crear, $ruc_crear, $email_crear, $fecha_nac_crear
    , $sexo_crear, $celular_crear, $estado_civil_crear;
    public $ver_documento_search, $ver_crear_persona;


    public function mount()
    {
        $this->titulo = 'Buscar Persona';
        $this->ver_documento_search= 'block';
        $this->ver_crear_persona = 'none';
        $this->sexo = Sexo::all();
    }

    public function render()
    {
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

    }
}
