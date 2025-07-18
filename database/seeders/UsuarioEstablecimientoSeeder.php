<?php

namespace Database\Seeders;

use App\Models\UsuarioEstablecimiento;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsuarioEstablecimientoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UsuarioEstablecimiento::create([
            'user_id' => 1,
            'establecimiento_id' => 1
        ]);
    }
}
