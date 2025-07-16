<?php

namespace Database\Seeders;

use App\Models\Entidad;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EntidadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Entidad::create([
            'razon_social' => 'Caja de Jubilaciones y Pensiones del Personal Municipal',
            'nombra_fantasia' => 'Caja de Jubilaciones y Pensiones del Personal Municipal',
            'ruc' => '80000492-2',
            'tipo_contribuyente' => 2,
            'tipo_regimen' => null,
            'email' => 'cajamunicipal.presidencia@gmail.com',
            'tipo_transaccion_id' => 2,
            'ambiente' => 0,
        ]);
    }
}
