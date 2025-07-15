<div class="col-lg-12 layout-spacing">
    <div class="statbox widget box box-shadow">
        <div class="widget-content widget-content-area">

            <h4 class="font-bold mb-3">PAGO TARIFA</h4>
            <h6 class="font-bold mb-1">DATOS DE LA PERSONA</h6>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="form-row mb-2">
                        <div class="form-group col-md-3">
                            <label for="documento_crear">Documento</label>
                            <input type="text" class="form-control text-right bg-white text-dark" value="{{$persona->documento}}" readonly>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="nombre_crear">Nombre</label>
                            <input type="text" class="form-control text-right bg-white text-dark" value="{{$persona->nombre}}" readonly>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="apellido_crear">Apellido</label>
                            <input type="text" class="form-control text-right bg-white text-dark" value="{{$persona->apellido}}" readonly>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="ruc">RUC</label>
                            <input wire:model.defer="ruc" type="text" class="form-control" placeholder="RUC">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="email">Correo</label>
                            <input wire:model.defer="email" type="text" class="form-control" placeholder="Correo">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="marca">Marca</label>
                            <input type="text" class="form-control text-right bg-white text-dark" value="{{$registro_diario->marca->descripcion}}" readonly>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="modelo">Modelo</label>
                            <input type="text" class="form-control text-right bg-white text-dark" value="{{$registro_diario->modelo->descripcion}}" readonly>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="color">Color</label>
                            <input type="text" class="form-control text-right bg-white text-dark" value="{{$registro_diario->color->descripcion}}" readonly>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="chapa">Nroº Chapa</label>
                            <input type="text" class="form-control text-right bg-white text-dark" value="{{$registro_diario->chapa}}" readonly>
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
                            <input type="text" wire:model="cantidad" class="form-control bg-white text-right text-dark" onkeyup="punto_decimal(this)">
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
                            <label for="total_pagado">Total Pagado</label>
                            <input type="text" wire:model="total_pagado" class="form-control bg-white text-right text-dark" onkeyup="punto_decimal(this)"
                            @if (!($modificar_total_pagar))
                                readonly
                            @endif>
                        </div>
                        <div class="form-group col-md-2 col-sm-12" style="display: {{$ver_vuelto}}">
                            <label for="vuelto">Vuelto</label>
                            <input type="text" class="form-control text-right bg-white text-dark" value="{{number_format($vuelto, 0, ".", ".")}}" readonly>
                        </div>

                    </div>

                    <button type="button" class="btn btn-primary" wire:click="calcular(15000)">
                        15.000
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
