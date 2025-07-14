<?php

namespace Database\Seeders;

use App\Models\Marca;
use App\Models\Modelo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MarcaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $marcasModelos = [
            'TOYOTA' => ['SIN ESPECIFICAR', 'Corolla', 'Hilux', 'Yaris', 'RAV4', 'Fortuner'],
            'HYUNDAI' => ['SIN ESPECIFICAR', 'Accent', 'Tucson', 'Santa Fe', 'Elantra', 'Creta'],
            'KIA' => ['SIN ESPECIFICAR', 'Rio', 'Sportage', 'Sorento', 'Cerato', 'Picanto'],
            'CHEVROLET' => ['SIN ESPECIFICAR', 'S10', 'Onix', 'Tracker', 'Spin', 'Cruze'],
            'NISSAN' => ['SIN ESPECIFICAR', 'Versa', 'Frontier', 'Sentra', 'X-Trail', 'March'],
            'FORD' => ['SIN ESPECIFICAR', 'Ranger', 'EcoSport', 'Fiesta', 'Focus', 'Explorer'],
            'VOLKSWAGEN' => ['SIN ESPECIFICAR', 'Gol', 'Polo', 'Amarok', 'T-Cross', 'Voyage'],
            'HONDA' => ['SIN ESPECIFICAR', 'Civic', 'CR-V', 'Fit', 'HR-V', 'City', 'CB125F', 'XR150L', 'Wave', 'Biz'],
            'MERCEDES-BENZ' => ['SIN ESPECIFICAR', 'Clase C', 'Clase A', 'GLC', 'Sprinter', 'Clase E'],
            'BMW' => ['SIN ESPECIFICAR', 'Serie 3', 'Serie 1', 'X3', 'X1', 'Serie 5'],
            'AUDI' => ['SIN ESPECIFICAR', 'A3', 'A4', 'Q5', 'Q3', 'A6'],
            'RENAULT' => ['SIN ESPECIFICAR', 'Kwid', 'Duster', 'Sandero', 'Logan', 'Oroch'],
            'PEUGEOT' => ['SIN ESPECIFICAR', '208', '2008', '3008', 'Partner', '308'],
            'FIAT' => ['SIN ESPECIFICAR', 'Strada', 'Mobi', 'Toro', 'Cronos', 'Fiorino'],
            'MAZDA' => ['SIN ESPECIFICAR', 'CX-5', '3', 'CX-3', 'BT-50', '6'],
            'SUZUKI' => ['SIN ESPECIFICAR', 'Swift', 'Vitara', 'Jimny', 'Baleno', 'S-Cross', 'GN125', 'Gixxer', 'Access'],
            'MITSUBISHI' => ['SIN ESPECIFICAR', 'L200', 'Montero', 'Outlander', 'ASX', 'Eclipse Cross'],
            'ISUZU' => ['SIN ESPECIFICAR', 'D-MAX', 'MU-X', 'Rodeo'],
            'JEEP' => ['SIN ESPECIFICAR', 'Renegade', 'Compass', 'Cherokee', 'Wrangler', 'Gladiator'],
            'CHERY' => ['SIN ESPECIFICAR', 'Tiggo 2', 'Tiggo 3', 'Tiggo 5', 'Arrizo 5'],
            'GEELY' => ['SIN ESPECIFICAR', 'Coolray', 'Azkarra', 'Emgrand 7', 'Emgrand X7'],
            'BYD' => ['SIN ESPECIFICAR', 'Dolphin', 'Tang', 'Han', 'Song Pro', 'Yuan Plus'],
            'TESLA' => ['SIN ESPECIFICAR', 'Model 3', 'Model S', 'Model X', 'Model Y'],
            'YAMAHA' => ['SIN ESPECIFICAR', 'Crypton', 'FZ', 'XTZ', 'YBR125'],
            'KAWASAKI' => ['SIN ESPECIFICAR', 'Z400', 'Versys 300', 'Ninja 250'],
            'BAJAJ' => ['SIN ESPECIFICAR', 'Boxer 150', 'Pulsar NS125', 'Discover'],
            'HAOJUE' => ['SIN ESPECIFICAR', 'NK150', 'HJ110-2C'],
            'OTRA MOTO' => ['SIN ESPECIFICAR', 'Modelo Genérico'],
            'OTRO' => ['SIN ESPECIFICAR', 'Modelo Genérico']
        ];



        foreach ($marcasModelos as $marcaNombre => $modelos) {
            $marca = Marca::firstOrCreate([
                'descripcion' => $marcaNombre,
                'user_id' => 1,
            ]);

            foreach ($modelos as $modeloNombre) {
                Modelo::firstOrCreate([
                    'descripcion' => $modeloNombre,
                    'marca_id' => $marca->id,
                    'user_id' => 1
                ]);
            }
        }

    }
}
