<?php

namespace Database\Seeders;

use App\Models\FormaCobro;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FormaCobroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cobro = ['EFECTIVO', 'TRANSFERENCIA BANCARIA', 'POST', 'QR'];

        foreach ($cobro as $item) {
            FormaCobro::firstOrCreate([
                'descripcion' => $item,
                'estado_id' => 1,
                'user_id' => 1,
            ]);
        }
    }
}
