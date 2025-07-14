<?php

namespace Database\Seeders;

use App\Models\Color;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $colores = [
            'SIN ESPECIFICAR',
            'BLANCO',
            'NEGRO',
            'GRIS',
            'PLATA',
            'AZUL',
            'ROJO',
            'VERDE',
            'AMARILLO',
            'NARANJA',
            'MARRÓN',
            'BEIGE',
            'BORDÓ',
            'CELESTE',
            'VIOLETA',
            'DORADO',
            'TURQUESA',
            'CHAMPAGNE',
            'OTRO'
        ];

        foreach ($colores as $color) {
            Color::firstOrCreate(['descripcion' => $color]);
        }

    }
}
