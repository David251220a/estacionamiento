<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getTienePlanActivoAttribute()
    {
        $fecha = Carbon::now()->toDateString();

        return $this->hasMany(PlanPersona::class, 'persona_id')
        ->where('estado_id', 1)
        ->where('fecha_fin', '>=', $fecha)
        ->exists(); // Devuelve true o false
    }

    public function vehiculo_ultimo_registro()
    {
        return $this->hasOne(Vehiculo::class, 'persona_id')->latest();
    }
}
