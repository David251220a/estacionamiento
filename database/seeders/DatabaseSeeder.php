<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Estado;
use App\Models\Sexo;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $this->call(RoleSeeder::class);

        User::firstOrCreate([
            'name' => 'Admin',
            'lastname' => 'Admin',
            'documento' => '4918642',
            'email' => 'admin@dev',
            'password' => Hash::make('admin123456'),
        ])->assignRole('admin');

        $estado = ['ACTIVO', 'INACTIVO'];

        foreach ($estado as $item) {
            Estado::firstOrCreate([
                'descripcion' => $item
            ]);
        }

        $sexo = ['SIN ESPECIFICAR', 'MASCULINO', 'FEMENINO'];

        foreach ($sexo as $item) {
            Sexo::firstOrCreate([
                'descripcion' => $item
            ]);
        }

        $this->call([
            ColorSeeder::class,
            MarcaSeeder::class,
            TipoVehiculoSeeder::class,
            PlanSeeder::class,
            EntidadSeeder::class,
            ActividadEconomicaSeeder::class,
            FormaCobroSeeder::class,
        ]);

    }
}
