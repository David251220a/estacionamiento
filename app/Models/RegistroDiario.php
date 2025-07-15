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
}
