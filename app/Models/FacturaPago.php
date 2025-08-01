<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacturaPago extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function tipo_pago()
    {
        return $this->belongsTo(Banco::class, 'banco_id');
    }

    public function banco()
    {
        return $this->belongsTo(Banco::class, 'banco_id');
    }

    public function cobroTipo()
    {
        return $this->belongsTo(FormaCobro::class, 'forma_cobro_id');
    }
}
