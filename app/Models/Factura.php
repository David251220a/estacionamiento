<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function facturaDetalle()
    {
        return $this->hasMany(FacturaDetalle::class, 'factura_id');
    }

    public function facturaPago()
    {
        return $this->hasMany(FacturaPago::class, 'factura_id');
    }

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona_id');
    }
}
