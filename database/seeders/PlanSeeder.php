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

        foreach ($plan as $item) {
            Plan::firstOrCreate([
                'descripcion' => $item,
                'precio' => 10000,
                'dias' => 1,
                'estado_id' => 1,
                'user_id' => 1,
            ]);
        }
    }
}
