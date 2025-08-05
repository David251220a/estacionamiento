<div class="col-lg-12 layout-spacing">
    <div class="statbox widget box box-shadow">
        <div class="widget-content widget-content-area">

            <h4 class="font-bold mb-3">PAGO TARIFA</h4>
            <h4 class="font-bold mb-3">TICKET:  {{str_pad($registro_diario->ticket, 5, '0', STR_PAD_LEFT)}}/{{$registro_diario->anio}}</h4>
            <h6 class="font-bold mb-1">DATOS DE LA PERSONA</h6>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="form-row mb-2">
                        <div class="form-group col-md-3">
                            <label for="documento_crear">Documento</label>
                            <input type="text" wire:model.defer='documento_crear' wire:blur='verificarDocumento' class="form-control text-right bg-white text-dark"
                            {{ ($modicar_persona == false ? 'readonly' : '' )}} onkeyup="punto_decimal(this)">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="nombre_crear">Nombre</label>
                            <input type="text" wire:model.defer='nombre_crear' class="form-control text-right bg-white text-dark" {{ ($modicar_persona == false ? 'readonly' : '' )}}>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="apellido_crear">Apellido</label>
                            <input type="text" wire:model.defer='apellido_crear' class="form-control text-right bg-white text-dark" {{ ($modicar_persona == false ? 'readonly' : '' )}}>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="ruc">RUC</label>
                            <input wire:model.defer="ruc" type="text" class="form-control text-dark" placeholder="RUC" {{ ($modicar_persona == false ? 'readonly' : '' )}}>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="email">Correo</label>
                            <input wire:model.defer="email" type="text" class="form-control text-dark" placeholder="Correo" {{ ($modicar_persona == false ? 'readonly' : '' )}}>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="marca">Marca</label>
                            <select wire:model="marca_id" class="form-control text-dark" {{ ($modicar_persona == false ? 'readonly' : '' )}}>
                                @foreach ($marcas as $item)
                                    <option value="{{$item->id}}">{{$item->descripcion}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="modelo">Modelo</label>
                            <select wire:model.defer="modelo_id" class="form-control text-dark" {{ ($modicar_persona == false ? 'readonly' : '' )}}>
                                @foreach ($modelos as $item)
                                    <option value="{{$item->id}}">{{$item->descripcion}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="color">Color</label>
                            <select wire:model.defer="color_id" class="form-control text-dark" {{ ($modicar_persona == false ? 'readonly' : '' )}}>
                                @foreach ($colores as $item)
                                    <option value="{{$item->id}}">{{$item->descripcion}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="chapa">Nroº Chapa</label>
                            <input type="text" wire:model.defer='nro_chapa' class="form-control text-right bg-white text-dark">
                        </div>
                        <div class="form-group col-md-2">
                            <br>
                            <button type="button" class="btn btn-info form-control" wire:click="editar_persona" style="display: {{$boton_agregar}}">
                                Agregar Persona
                            </button>

                            <button type="button" class="btn btn-danger form-control" wire:click="cancelar_persona" style="display: {{$boton_cancelar}}">
                                Cancelar
                            </button>
                        </div>

                        <div class="form-group col-md-2">
                            <br>
                            <button type="button" class="btn btn-success form-control" wire:click="grabar_persona" style="display: {{$boton_grabar_persona}}">
                                Grabar Persona
                            </button>
                        </div>

                    </div>

                </div>
            </div>

            <h6 class="font-bold mb-1">CALCULO TARIFA</h6>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="form-row mb-2">
                        <div class="form-group col-md-2">
                            <label for="documento_crear">Plan</label>
                            <select wire:model="plan_id" class="form-control">
                                @foreach ($planes as $item)
                                    <option value="{{$item->id}}">{{$item->descripcion}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="precio">Precio</label>
                            <input type="text" class="form-control text-right bg-white text-dark" value="{{number_format($precio, 0, ".", ".")}}" readonly>
                        </div>
                        <div class="form-group col-md-2 col-sm-12" style="display: {{$ver_hora}}">
                            <label for="hora_ingreso">Hora Ingreso</label>
                            <input type="time" class="form-control bg-white text-dark" value="{{$registro_diario->hora_ingreso}}" readonly>
                        </div>
                        <div class="form-group col-md-2 col-sm-12" style="display: {{$ver_hora}}">
                            <label for="hora_computada">Horas Computadas</label>
                            <input type="text" class="form-control text-right bg-white text-dark" value="{{number_format($hora_computada, 0, ".", ".")}}" readonly>
                        </div>
                        <div class="form-group col-md-2 col-sm-12" style="display: {{$ver_resto}}">
                            <label for="cantidad">Cantidad {{$titulo_cantidad}}</label>
                            <input type="text" wire:model.defer="cantidad" wire:change="validarCantidad" class="form-control bg-white text-right text-dark" onkeyup="punto_decimal(this)">
                        </div>
                        <div class="form-group col-md-2 col-sm-12">
                            <label for="total_a_pagar">Total a Pagar</label>
                            <input type="text" class="form-control text-right bg-white text-dark" value="{{number_format($total_a_pagar, 0, ".", ".")}}" readonly>
                        </div>
                    </div>
                </div>
            </div>

            <h6 class="font-bold mb-1">FORMA DE PAGO</h6>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="form-row mb-2">
                        <div class="form-group col-md-2">
                            <label for="forma_pago_id">Forma pago</label>
                            <select wire:model="forma_pago_id" class="form-control">
                                @foreach ($forma_cobros as $item)
                                    <option value="{{$item->id}}">{{$item->descripcion}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-2" style="display: {{$verBanco}}">
                            <label for="banco_id">Banco</label>
                            <select wire:model="banco_id" class="form-control">
                                @foreach ($bancos as $item)
                                    <option value="{{$item->id}}">{{$item->descripcion}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-2 col-sm-12">
                            <label for="monto_abonado">Monto</label>
                            <input type="text" wire:model.defer="monto_abonado" wire:change="validarTotalPagar" class="form-control bg-white text-right text-dark" onkeyup="punto_decimal(this)">
                        </div>

                        <div class="form-group col-md-1 col-sm-12">
                            <br>
                            <button type="button" wire:click="agregar_forma_cobro" class="btn btn-info form-control" style="display: {{$ver_boton_agregar_forma_pago}}">+</button>
                        </div>

                    </div>
                </div>

                <div class="col-lg-12 col-md-12 col-sm-12" style="display: {{$ver_forma_pago_dos}}">
                    <div class="form-row mb-2">
                        <div class="form-group col-md-2">
                            <label for="forma_pago_id_dos">Forma pago</label>
                            <select wire:model="forma_pago_id_dos" class="form-control">
                                @foreach ($forma_cobros_dos as $item)
                                    <option value="{{$item->id}}">{{$item->descripcion}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-2" style="display: {{$verBanco_dos}}">
                            <label for="banco_id_dos">Banco</label>
                            <select wire:model="banco_id_dos" class="form-control">
                                @foreach ($bancos_dos as $item)
                                    <option value="{{$item->id}}">{{$item->descripcion}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-2 col-sm-12">
                            <label for="monto_abonado_dos">Monto</label>
                            <input type="text" wire:model.defer="monto_abonado_dos" wire:change="validarTotalPagar" class="form-control bg-white text-right text-dark" onkeyup="punto_decimal(this)">
                        </div>

                        <div class="form-group col-md-1 col-sm-12">
                            <br>
                            <button type="button" wire:click="quitar_forma_cobro" class="btn btn-danger form-control" style="display: {{$eliminar_forma_pago}}">-</button>
                        </div>

                    </div>
                </div>

                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="form-row mb-2">
                        <div class="form-group col-md-2 col-sm-12">
                            <label for="total_general_abonado">Total General Abonado</label>
                            <input type="text" class="form-control bg-white text-right text-dark" value="{{$total_general_abonado}}" readonly>
                        </div>

                        <div class="form-group col-md-2 col-sm-12" style="display: {{$ver_vuelto}}">
                            <label for="vuelto">Vuelto</label>
                            <input type="text" class="form-control text-right bg-white text-dark" value="{{number_format($vuelto, 0, ".", ".")}}" readonly>
                        </div>

                    </div>

                    <button type="button" class="btn btn-primary" wire:click="calcular(10000)">
                        10.000
                    </button>

                    <button type="button" class="btn btn-primary" wire:click="calcular(20000)">
                        20.000
                    </button>

                    <button type="button" class="btn btn-primary" wire:click="calcular(50000)">
                        50.000
                    </button>

                    <button type="button" class="btn btn-primary" wire:click="calcular(100000)">
                        100.000
                    </button>

                    <button type="button" class="btn btn-primary" wire:click="calcular(150000)">
                        150.000
                    </button>

                    <button type="button" class="btn btn-primary" wire:click="calcular(200000)">
                        200.000
                    </button>

                    <button type="button" class="btn btn-primary" wire:click="calcular(300000)">
                        300.000
                    </button>

                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-4 mt-3">
                    <button
                        type="button"
                        wire:click="guardar()"
                        :disabled="$wire.procesando"
                        class="btn btn-success"
                    >
                        <span wire:loading.remove wire:target="registrar_diario">Procesar</span>
                        <span wire:loading wire:target="registrar_diario">Procesando...</span>
                    </button>
                </div>

            </div>

        </div>
    </div>
</div>
