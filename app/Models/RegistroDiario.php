<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistroDiario extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona_id');
    }

    public function marca()
    {
        return $this->belongsTo(Marca::class, 'marca_id');
    }

    public function modelo()
    {
        return $this->belongsTo(Modelo::class, 'modelo_id');
    }

    public function color()
    {
        return $this->belongsTo(Color::class, 'color_id');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    public function factura()
    {
        return $this->hasOne(Factura::class, 'registro_diario_id')->where('estado_id', 1);
    }
}
