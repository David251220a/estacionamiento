<?php

namespace App\Http\Livewire\Ingreso;

use App\Models\Banco;
use App\Models\Color;
use App\Models\Entidad;
use App\Models\Establecimiento;
use App\Models\Factura;
use App\Models\FacturaDetalle;
use App\Models\FacturaPago;
use App\Models\FormaCobro;
use App\Models\Marca;
use App\Models\Modelo;
use App\Models\Numeracion;
use App\Models\Persona;
use App\Models\Plan;
use App\Models\PlanPersona;
use App\Models\RegistroDiario;
use App\Models\Timbrado;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class IngresoCobro extends Component
{
    public $registro_diario, $persona, $documento_crear, $nombre_crear, $apellido_crear, $ruc, $email
    , $marca_id, $marcas, $modelo_id, $modelos, $color_id, $colores, $nro_chapa, $planes, $modicar_persona
    , $boton_agregar, $boton_cancelar, $boton_grabar_persona, $plan_id, $precio, $ver_hora, $ver_resto
    , $hora_computada, $total_a_pagar, $total_pagado, $vuelto, $cantidad, $hora_salida, $titulo_cantidad
    , $forma_pago_id, $forma_cobros, $banco_id, $bancos, $verBanco, $ver_vuelto, $modificar_total_pagar
    , $procesando, $factura, $total_general_abonado, $ver_forma_pago_dos, $forma_pago_id_dos, $forma_cobros_dos
    , $monto_abonado_dos, $verBanco_dos, $bancos_dos, $banco_id_dos, $ver_boton_agregar_forma_pago, $monto_abonado
    , $validar_suma_dos, $eliminar_forma_pago;

    public function mount(RegistroDiario $registro_diario)
    {
        $this->registro_diario = $registro_diario;
        $this->persona = $this->registro_diario->persona;
        $this->monto_abonado_dos = 0;
        $this->monto_abonado = 0;
        $this->ver_boton_agregar_forma_pago = 'block';
        $this->eliminar_forma_pago = 'none';
        $this->validar_suma_dos = false;
        // TODO REFERENTE A PLAN
        $this->planes = Plan::all();
        $this->plan_id = $this->registro_diario->plan_id;
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
        //FORMA DE COBRO
        $this->ver_forma_pago_dos = 'none';
        $this->forma_cobros = FormaCobro::whereIn('id', [1, 3, 4, 5, 16, 21])
        ->get();
        $this->forma_pago_id = 1;
        $this->updatedFormaPagoId($this->forma_pago_id);
        $this->bancos = Banco::all();
        $this->banco_id = 1;
        // FORMA DE COBRO DOS
        $this->ver_forma_pago_dos = 'none';
        $this->forma_cobros_dos = FormaCobro::whereIn('id', [1, 3, 4, 5, 16, 21])
        ->get();
        $this->forma_pago_id_dos = 1;
        $this->updatedFormaPagoId($this->forma_pago_id_dos);
        $this->bancos_dos = Banco::all();
        $this->banco_id_dos = 1;
        //RELLENAR LOS DATOS DE LA PERSONA Y VEHICULO
        $this->colores = Color::all();
        $this->color_id = $registro_diario->color_id;
        $this->marcas = Marca::all();
        $this->marca_id = $registro_diario->marca_id;
        $this->modelos = Modelo::where('marca_id', $this->marca_id)
        ->get();
        $this->modelo_id = $registro_diario->modelo_id;
        $this->documento_crear = $this->persona->documento;
        $this->nombre_crear = $this->persona->nombre;
        $this->apellido_crear = $this->persona->apellido;
        $this->ruc = $this->persona->ruc;
        $this->email = $this->persona->email;
        $this->nro_chapa = $registro_diario->chapa;
        $this->modicar_persona = false;
        $this->boton_agregar = 'block';
        $this->boton_cancelar = 'none';
        $this->boton_grabar_persona = 'none';
        $this->procesando = false;
    }

    public function updatedMarcaId($value)
    {
        $this->modelos = Modelo::where('marca_id', $value)
        ->get();
        $this->modelo_id = $this->modelos[0]->id;
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
            $this->cantidad = 1;
        }else{
            $this->ver_hora = 'none';
            $this->ver_resto = 'block';
            $this->total_a_pagar = $this->precio * $this->cantidad;
        }

        $this->validarTotalPagar();
    }

    public function validarCantidad()
    {
        if ($this->plan_id != 1) {

            // Verificar que sea número
            $value = $this->cantidad;
            if (!is_numeric($value) || $value <= 0) {
                $this->emit('mensaje_error', 'La cantidad debe ser un número válido mayor a cero.');
                $this->cantidad = 1; // valor por defecto si querés
                $value = 1;
                return false;
            }

            $this->total_a_pagar = $this->precio * $value;
            $this->validarTotalPagar();
        }
    }

    public function validarTotalPagar()
    {
        $monto_abonado = str_replace('.', '', $this->monto_abonado);
        $monto_abonado_dos = str_replace('.', '', $this->monto_abonado_dos);
        if (!is_numeric($monto_abonado) || $monto_abonado <= 0 || empty($monto_abonado)) {
            $monto_abonado = 0;
        }
        if (!is_numeric($monto_abonado_dos) || $monto_abonado_dos <= 0 || empty($monto_abonado_dos)) {
            $monto_abonado_dos = 0;
        }

        $this->vuelto = ($monto_abonado + $monto_abonado_dos) - $this->total_a_pagar;
        $this->total_general_abonado = number_format(($monto_abonado + $monto_abonado_dos), 0, ".", ".");
    }

    public function updatedFormaPagoId($value)
    {
        if($value == 1){
            $this->verBanco = 'none';
            $this->ver_vuelto = 'block';
            $this->banco_id = 1;
        }else{
            $this->verBanco = 'true';
            $this->ver_vuelto = 'block';
            $this->banco_id = 1;
        }

        $this->validarTotalPagar();
    }

    public function updatedFormaPagoIdDos($value)
    {
        if($value == 1){
            $this->verBanco_dos = 'none';
            $this->ver_vuelto = 'block';
            $this->banco_id_dos = 1;
        }else{
            $this->verBanco_dos = 'true';
            $this->ver_vuelto = 'block';
            $this->banco_id_dos = 1;
        }

        $this->validarTotalPagar();
    }

    public function calcular($value)
    {
        $this->monto_abonado = number_format($value, 0, ".", ".");
        $this->validarTotalPagar();
    }

    public function render()
    {
        return view('livewire.ingreso.ingreso-cobro');
    }

    public function editar_persona()
    {
        $this->boton_agregar = 'none';
        $this->boton_cancelar = 'block';
        $this->boton_grabar_persona = 'block';
        $this->modicar_persona = true;
    }

    public function cancelar_persona()
    {
        $this->persona = $this->registro_diario->persona;
        $this->marca_id = $this->registro_diario->marca_id;
        $this->modelos = Modelo::where('marca_id', $this->marca_id)
        ->get();
        $this->modelo_id = $this->registro_diario->modelo_id;
        $this->modelo_id = $this->registro_diario->modelo_id;
        $this->documento_crear = $this->persona->documento;
        $this->nombre_crear = $this->persona->nombre;
        $this->apellido_crear = $this->persona->apellido;
        $this->ruc = $this->persona->ruc;
        $this->email = $this->persona->email;
        $this->nro_chapa = $this->registro_diario->chapa;
        $this->modicar_persona = false;
        $this->boton_agregar = 'block';
        $this->boton_cancelar = 'none';
        $this->boton_grabar_persona = 'none';
        $this->modicar_persona = false;
    }

    public function verificarDocumento()
    {
        $documnento = str_replace('.', '', $this->documento_crear);

        $persona = Persona::where('documento', $documnento)
        ->first();

        if($persona){
            $this->persona = $persona;
            $this->nombre_crear = $this->persona->nombre;
            $this->apellido_crear = $this->persona->apellido;
            $this->ruc = $this->persona->ruc;
            $this->email = $this->persona->email;
        } else {
            $this->persona = null;
            $this->nombre_crear = '';
            $this->apellido_crear = '';
            $this->ruc = '';
            $this->email = '';
        }

    }

    public function grabar_persona()
    {
        $documento = str_replace('.', '', $this->documento_crear);

        if (empty($documento)){
           $this->emit('mensaje_error', 'El numero de documento no puede ser vacio.');
            return false;
        }

        if($documento == 0){
            $this->emit('mensaje_error', 'El numero de documento no puede ser cero.');
            return false;
        }

        if (empty($this->nombre_crear)){
           $this->emit('mensaje_error', 'El nomrbe de la persona no puede ser vacio.');
            return false;
        }

        if (empty($this->apellido_crear)){
           $this->emit('mensaje_error', 'El nomrbe de la persona no puede ser vacio.');
            return false;
        }

        if (empty($this->email)){
           $this->emit('mensaje_error', 'El nomrbe de la persona no puede ser vacio.');
            return false;
        }

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $this->emit('mensaje_error', 'El correo no es válido.');
            return false;
        }

        try{

            if($this->persona == null){
                $persona = Persona::create([
                    'documento' => $documento,
                    'nombre' => $this->nombre_crear,
                    'apellido' => $this->apellido_crear,
                    'fecha_nacimiento' => null,
                    'sexo_id' => 1,
                    'estado_civil' => 0,
                    'email' => $this->email,
                    'celular' => '',
                    'ruc' => $this->ruc,
                    'estado_id' => 1,
                    'user_id' => 1,
                    'usuario_modificacion' => 1
                ]);
                $this->persona = $persona;
            } else {
                $persona = Persona::find($this->persona->id);
                $persona->update([
                    'nombre' => $this->nombre_crear,
                    'apellido' => $this->apellido_crear,
                    'email' => $this->email,
                    'ruc' => $this->ruc,
                    'usuario_modificacion' => 1
                ]);

                $this->persona = $persona;

            }

            $registro = RegistroDiario::find($this->registro_diario->id);
            $registro->update([
                'persona_id' => $this->persona->id,
                'marca_id' => $this->marca_id,
                'modelo_id' => $this->modelo_id,
                'color_id' => $this->color_id,
                'chapa' => $this->nro_chapa
            ]);


            $this->boton_agregar = 'block';
            $this->boton_cancelar = 'none';
            $this->boton_grabar_persona = 'none';
            $this->modicar_persona = false;
            $this->emit('mensaje_exitoso', 'Persona Agregada Correctamente');

        } catch (\Exception $e) {
            $this->emit('mensaje_error', 'Ocurrió un error al generar la factura: ' . $e->getMessage());
            return false;
        }
    }

    public function agregar_forma_cobro()
    {
        $this->ver_forma_pago_dos = 'block';
        $this->ver_boton_agregar_forma_pago = 'none';
        $this->eliminar_forma_pago = 'block';
        $this->forma_pago_id_dos = 1;
        $this->validar_suma_dos = true;
        $this->updatedFormaPagoIdDos($this->forma_pago_id_dos);
    }

    public function quitar_forma_cobro(){
        $this->ver_forma_pago_dos = 'none';
        $this->ver_boton_agregar_forma_pago = 'block';
        $this->eliminar_forma_pago = 'none';
        $this->validar_suma_dos = false;
        $this->forma_pago_id_dos = 1;
        $this->banco_id_dos = 1;
        $this->monto_abonado_dos = 0;
        $this->updatedFormaPagoIdDos($this->forma_pago_id_dos);
    }

    public function guardar()
    {
        $this->procesando = true;

        $total_abonado = str_replace('.', '', $this->total_general_abonado);
        $total_a_pagar = str_replace('.', '', $this->total_a_pagar);
        $monto_primero = str_replace('.', '', $this->monto_abonado);
        $monto_segundo = str_replace('.', '', $this->monto_abonado_dos);

        if (empty($monto_primero)){
            $this->emit('mensaje_error', 'El monto abonado de la primera forma de pago no debe ser vacio.');
            $this->procesando = false;
            return false;
        }

        if ($monto_primero == 0){
            $this->emit('mensaje_error', 'El monto abonado de la primera forma de pago no debe ser cero.');
            $this->procesando = false;
            return false;
        }

        if ($this->validar_suma_dos == true){
            if (empty($monto_segundo)){
                $this->emit('mensaje_error', 'El monto abonado de la segundo forma de pago no debe ser vacio.');
                $this->procesando = false;
                return false;
            }

            if ($monto_segundo == 0){
                $this->emit('mensaje_error', 'El monto abonado de la segundo forma de pago no debe ser cero.');
                $this->procesando = false;
                return false;
            }
        }


        if ($total_abonado < $total_a_pagar){
            $this->emit('mensaje_error', 'El total pagado no puede ser menor al total a pagar.');
            $this->procesando = false;
            return false;
        }

        if ($this->forma_pago_id <> 1){
            if ($this->banco_id == 1){
                $this->emit('mensaje_error', 'Debe seleccionar un banco en la primera forma de pago.');
                $this->procesando = false;
                return false;
            }
        }

        if ($this->forma_pago_id_dos <> 1){
            if ($this->banco_id_dos == 1){
                $this->emit('mensaje_error', 'Debe seleccionar un banco en el segundo forma de pago.');
                $this->procesando = false;
                return false;
            }
        }

        $factura_cobrado = Factura::where('registro_diario_id', $this->registro_diario->id)
        ->where('estado_id', 1)
        ->exists();

        if ($factura_cobrado){
            return redirect()->route('')->withErrors('El pago por el servicio ya se encuentra en estado pagado.');
        }

        try{
            DB::transaction(function () {
                $fecha = Carbon::now()->toDateString();
                $_total_abonado = str_replace('.', '', $this->total_general_abonado);
                $_total_a_pagar = str_replace('.', '', $this->total_a_pagar);
                $monto_primero = str_replace('.', '', $this->monto_abonado);
                $monto_segundo = str_replace('.', '', $this->monto_abonado_dos);
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

                // $numero_factura = $numeracion->numero_siguiente;
                $numero_factura = 0;
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
                $detalleCantidad = 0;
                if ($this->plan_id == 1){
                    $detalleCantidad = $this->hora_computada;
                } else {
                    $detalleCantidad = $this->cantidad;
                }

                FacturaDetalle::create([
                    'factura_id' => $factura->id,
                    'plan_id' => $this->plan_id,
                    'plan_persona' => $plan_persona,
                    'monto' => $_total_a_pagar,
                    'cantidad' => $detalleCantidad,
                    'hora_ingreso' => $this->registro_diario->hora_ingreso,
                    'hora_salida' => $hora_salida,
                ]);

                FacturaPago::create([
                    'factura_id' => $factura->id,
                    'forma_cobro_id' => $this->forma_pago_id,
                    'banco_id' => $this->banco_id,
                    'monto' => $monto_primero,
                ]);

                if ($this->validar_suma_dos == true){
                    FacturaPago::create([
                        'factura_id' => $factura->id,
                        'forma_cobro_id' => $this->forma_pago_id_dos,
                        'banco_id' => $this->banco_id_dos,
                        'monto' => $monto_segundo,
                    ]);
                }

                $registro = RegistroDiario::find($this->registro_diario->id);

                $registro->update([
                    'facturado' => 1,
                    'hora_salida' => $hora_salida,
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

}
