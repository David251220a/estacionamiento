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

    public function tipodocumentofactura()
    {
        return $this->belongsTo(TipoDocumento::class, 'tipo_documento_id');
    }

    public function tipoTransaccionFactura()
    {
        return $this->belongsTo(TipoTransaccion::class, 'tipo_transaccion_id');
    }

    public function establecimiento()
    {
        return $this->belongsTo(Establecimiento::class, 'establecimiento_id');
    }


    public function registroDiario()
    {
        return  $this->belongsTo(RegistroDiario::class, 'registro_diario_id');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function PersonaPlan()
    {
        return $this->belongsTo(PlanPersona::class, 'plan_persona');
    }

}
