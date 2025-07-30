<?php

namespace Database\Seeders;

use App\Models\Persona;
use Illuminate\Database\Seeder;

class PersonaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Persona::create([
            'documento' => 0,
            'nombre' => 'SIN',
            'apellido' => 'NOMBRE',
            'fecha_nacimiento' => null,
            'sexo_id' => 1,
            'estado_civil' => 0,
            'email' => 'davidortiz25122010@gmail.com',
            'celular' => '',
            'ruc' => 0,
            'estado_id' => 1,
            'user_id' => 1,
            'usuario_modificacion' => 1
        ]);
    }
}
