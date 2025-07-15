<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use HasFactory;

    public function getTienePlanActivoAttribute()
    {
        $fecha = Carbon::now()->toDateString();

        return $this->hasMany(PlanPersona::class, 'persona_id')
        ->where('estado_id', 1)
        ->where('fecha_fin', '>=', $fecha)
        ->exists(); // Devuelve true o false
    }
}
