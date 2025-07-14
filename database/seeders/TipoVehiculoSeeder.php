<?php

namespace Database\Seeders;

use App\Models\TipoVehiculo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoVehiculoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $estado = ['SIN ESPECIFICAR', 'AUTOMOVIL', 'MOTOCICLETA'];

        foreach ($estado as $item) {
            TipoVehiculo::firstOrCreate([
                'descripcion' => $item
            ]);
        }
    }
}
