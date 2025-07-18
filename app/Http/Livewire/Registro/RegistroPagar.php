<?php

namespace App\Http\Livewire\Registro;

use App\Models\Banco;
use App\Models\Entidad;
use App\Models\Establecimiento;
use App\Models\Factura;
use App\Models\FacturaDetalle;
use App\Models\FacturaPago;
use App\Models\FormaCobro;
use App\Models\Numeracion;
use App\Models\Plan;
use App\Models\PlanPersona;
use App\Models\RegistroDiario;
use App\Models\Timbrado;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class RegistroPagar extends Component
{
    public $registro_diario, $persona, $planes, $forma_cobros, $bancos, $factura;
    public $email, $ruc, $plan_id, $precio, $titulo_cantidad, $cantidad, $total_a_pagar
    , $hora_computada, $total_pagado, $banco_id, $forma_pago_id, $vuelto, $hora_salida;
    public $ver_hora, $ver_resto, $verBanco, $modificar_total_pagar, $ver_vuelto;
    public $procesando = false;

    public function mount(RegistroDiario $registro_diario)
    {
        $this->registro_diario = $registro_diario;
        $this->persona = $registro_diario->persona;
        $this->ruc = $this->persona->ruc;
        $this->email = $this->persona->email;
        $this->planes = Plan::all();
        $this->forma_cobros = FormaCobro::all();
        $this->forma_pago_id = 1;
        $this->bancos = Banco::all();
        $this->banco_id = 1;
        $this->plan_id = $registro_diario->plan_id;
        $this->titulo_cantidad = 'Semana';
        $this->modificar_total_pagar = true;
        $this->verBanco = 'none';
        $this->ver_vuelto = 'block';
        $this->vuelto = 0;
        $this->cantidad = 1;
        $this->hora_salida = Carbon::now();
        if ($this->plan_id == 1){
            $this->ver_hora = 'block';
            $this->ver_resto = 'none';
        }else{
            $this->ver_hora = 'none';
            $this->ver_resto = 'block';
        }
        $this->updatedPlanId($this->plan_id);
        $this->total_pagado = number_format($this->total_a_pagar, 0, ".", ".");
    }

    public function updatedCantidad($value)
    {
        if ($this->plan_id != 1) {
            $this->total_a_pagar = $this->precio * $value;
            $this->total_pagado = number_format($this->total_a_pagar, 0, ".", ".");
            $this->vuelto = str_replace('.', '', $this->total_pagado) - $this->total_a_pagar;
        }
    }

    public function updatedPlanId($value)
    {
        $plan = Plan::find($value);
        $this->precio = $plan->precio;
        if($value == 1){
            $this->ver_hora = 'block';
            $this->ver_resto = 'none';
            $horaIngreso = Carbon::parse($this->registro_diario->hora_ingreso);
            $horaSalida = $this->hora_salida;
            $minutos = $horaIngreso->diffInMinutes($horaSalida);
            $horasCobradas = ceil($minutos / 60);
            $this->hora_computada = $horasCobradas;
            $this->total_a_pagar = $horasCobradas * $this->precio;
            $this->total_pagado = number_format($this->total_a_pagar, 0, ".", ".");
            $this->vuelto = str_replace('.', '', $this->total_pagado) - $this->total_a_pagar;
        }else{
            $this->ver_hora = 'none';
            $this->ver_resto = 'block';
            $this->total_a_pagar = $this->precio * $this->cantidad;
            $this->total_pagado = number_format($this->total_a_pagar, 0, ".", ".");
            $this->vuelto = str_replace('.', '', $this->total_pagado) - $this->total_a_pagar;
        }
    }

    public function updatedFormaPagoId($value)
    {
        if($value == 1){
            $this->verBanco = 'none';
            $this->ver_vuelto = 'block';
            $this->vuelto = str_replace('.', '', $this->total_pagado) - $this->total_a_pagar;
            $this->modificar_total_pagar = true;
        }else{
            $this->verBanco = 'true';
            $this->ver_vuelto = 'none';
            $this->modificar_total_pagar = false;
            $this->vuelto = 0;
            $this->total_pagado = number_format($this->total_a_pagar, 0, ".", ".");
        }
    }

    public function updatedTotalPagado($value)
    {
        if($this->forma_pago_id == 1){
            $this->vuelto = str_replace('.', '', $value) - $this->total_a_pagar;
        }
    }


    public function calcular($value)
    {
        if($this->forma_pago_id == 1){
            $this->vuelto = $value - $this->total_a_pagar;
            $this->total_pagado = number_format($value, 0, ".", ".");
        }
    }

    public function render()
    {
        return view('livewire.registro.registro-pagar');
    }

    public function guardar()
    {
        $this->procesando = true;

        $total_abonado = str_replace('.', '', $this->total_pagado);
        $total_a_pagar = str_replace('.', '', $this->total_a_pagar);

        if ($total_abonado < $total_a_pagar){
            $this->emit('mensaje_error', 'El total pagado no puede ser al total a pagar.');
            $this->procesando = false;
            return false;
        }

        if ($this->forma_pago_id <> 1){
            if ($this->banco_id == 1){
                $this->emit('mensaje_error', 'Debe seleccionar un banco.');
                $this->procesando = false;
                return false;
            }
        }

        try{
            DB::transaction(function () {

                $fecha = Carbon::now()->toDateString();
                $_total_abonado = str_replace('.', '', $this->total_pagado);
                $_total_a_pagar = str_replace('.', '', $this->total_a_pagar);
                $vuelto = $_total_abonado - $_total_a_pagar;
                $hora_salida = $this->hora_salida->format('H:i');
                $tipoDocumento = 1;
                $usuario = User::find(auth()->user()->id);
                $entidad = Entidad::find(1);
                $usuario_establecimiento = $usuario->usuarioEstablecimiento;
                $establecimiento = Establecimiento::find($usuario_establecimiento->establecimiento_id)
                ->first();
                $timbrado = Timbrado::where('entidad_id', $entidad->id)
                ->where('estado_id', 1)
                ->first();

                $numeracion = Numeracion::where('timbrado_id', $timbrado->id)
                ->where('establecimiento_id', $establecimiento->id)
                ->where('tipo_documento_id', $tipoDocumento)
                ->where('estado_id', 1)
                ->lockForUpdate()
                ->first();

                if (!$timbrado) {
                    throw new \Exception('No se encontró un timbrado activo.');
                }

                $numero_factura = $numeracion->numero_siguiente;
                $plan_persona = 0;

                if($this->plan_id <> 1){
                    if(($this->plan_id == 2) && ($this->cantidad == 1)){
                        $plan_persona = 0;
                    }else{
                        $plan_persona = $this->crear_plan_persona($this->plan_id, $this->cantidad);
                    }
                }

                $concepto = $this->concepto_construir($this->plan_id, $this->cantidad, $this->hora_computada);

                $factura = Factura::create([
                    'persona_id' => $this->persona->id,
                    'registro_diario_id' => $this->registro_diario->id,
                    'plan_id' => $this->plan_id,
                    'vehiculo_id' => $this->persona->vehiculo_ultimo_registro->id,
                    'timbrado_id' => $timbrado->id,
                    'establecimiento_id' => $establecimiento->id,
                    'numero_factura' => $numero_factura,
                    'plan_persona' => $plan_persona,
                    'fecha_factura' => $fecha,
                    'tipo_documento_id' => 1,
                    'tipo_transaccion_id' => 2,
                    'condicion_pago' => 1,
                    'concepto' => $concepto,
                    'monto_total' => $_total_a_pagar,
                    'monto_abonado' => $_total_abonado,
                    'monto_devuelto' => $vuelto,
                    'estado_id' => 1,
                    'fecha_anulado' => null,
                    'user_id' => auth()->user()->id,
                    'usuario_anulacion' => null,
                    'motivo_anulacion' => null,
                ]);

                $this->factura = $factura;

                FacturaDetalle::create([
                    'factura_id' => $factura->id,
                    'plan_id' => $this->plan_id,
                    'plan_persona' => $plan_persona,
                    'monto' => $_total_a_pagar,
                    'cantidad' => 1,
                    'hora_ingreso' => $this->registro_diario->hora_ingreso,
                    'hora_salida' => $hora_salida,
                ]);

                FacturaPago::create([
                    'factura_id' => $factura->id,
                    'forma_cobro_id' => $this->forma_pago_id,
                    'banco_id' => $this->banco_id,
                    'monto' => $_total_a_pagar,
                ]);

                $registro = RegistroDiario::find($this->registro_diario->id);

                $registro->update([
                    'facturado' => 1,
                    'user_id' => auth()->user()->id,
                ]);

                $numeracion->numero_siguiente += 1;
                $numeracion->save();

            });

            return redirect()->route('factura.show', $this->factura)->with('message', 'Facturado correctamente.');

        } catch (\Throwable $e) {
            $this->emit('mensaje_error', 'Ocurrió un error al generar la factura: ' . $e->getMessage());
            $this->procesando = false;
            return false;
        }

    }

    public function crear_plan_persona($plan_id, $cantidad)
    {

        $fecha_actual = Carbon::now();

        switch ($plan_id) {
            case 2:
                $fecha = $fecha_actual->copy()->addDays($cantidad)->toDateString();
                break;
            case 3:
                $fecha = $fecha_actual->copy()->addWeeks($cantidad)->toDateString();
                break;
            case 4:
                $fecha = $fecha_actual->copy()->addYears($cantidad)->toDateString();
                break;
        }

        $plan_persona = PlanPersona::create([
            'persona_id' => $this->persona->id,
            'plan_id' => $plan_id,
            'fecha_inicio' => $fecha_actual->toDateString(),
            'fecha_fin' => $fecha,
            'cantidad' => $cantidad,
            'estado_id' => 1,
            'user_id' => auth()->user()->id,
        ]);

        return $plan_persona->id;

    }

    public function concepto_construir($plan_id, $cantidad, $hora_computada)
    {
        $concepto = '';

        switch ($plan_id) {
            case 1: // Plan por hora
                $concepto = 'Estacionamiento por ' . $hora_computada . ' hora(s)';
                break;

            case 2: // Plan diario
                $concepto = 'Estacionamiento por ' . $cantidad . ' día(s)';
                break;

            case 3: // Plan semanal
                $concepto = 'Estacionamiento por ' . $cantidad . ' semana(s)';
                break;

            case 4: // Plan anual
                $concepto = 'Estacionamiento por ' . $cantidad . ' año(s)';
                break;

            default:
                $concepto = 'Plan desconocido';
                break;
        }

        return $concepto;
    }

}
