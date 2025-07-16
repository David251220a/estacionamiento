<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $plan = ['HORA', 'DIA', 'SEMANAL', 'MENSUAL', 'ANUAL'];
        $montos = [10000, 30000, 120000, 300000, 3600000];

        foreach ($plan as $index => $item) {
            Plan::firstOrCreate([
                'descripcion' => $item,
                'precio' => $montos[$index],
                'dias' => 1,
                'estado_id' => 1,
                'user_id' => 1,
            ]);
        }
    }
}
