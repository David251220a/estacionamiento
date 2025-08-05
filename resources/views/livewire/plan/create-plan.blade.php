<div class="col-lg-12 layout-spacing">
    <div class="statbox widget box box-shadow">
        <div class="widget-content widget-content-area">

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 filtered-list-search mx-auto">
                    <div class="alert alert-icon-left alert-light-success mb-4" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-triangle">
                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                        <line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12" y2="17"></line></svg>
                        Crear Plan Persona
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="form-row mb-2">
                        <div class="form-group col-md-3">
                            <label for="documento_crear">Documento</label>
                            <input wire:model.defer="documento" type="text" class="form-control" placeholder="Documento" onkeyup="punto_decimal(this)" wire:blur='verificarDocumento'>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="nombre">Nombre</label>
                            <input wire:model.defer="nombre" type="text" class="form-control" placeholder="Nombre">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="apellido">Apellido</label>
                            <input wire:model.defer="apellido" type="text" class="form-control" placeholder="Apellido">
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
                            <label for="fecha_nac">Fecha Nacimiento</label>
                            <input wire:model.defer="fecha_nac" type="date" class="form-control">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="sexo_id">Sexo</label>
                            <select  wire:model.defer="sexo_id" class="form-control">
                                @foreach ($sexo as $item)
                                    <option value="{{$item->id}}">{{$item->descripcion}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="celular">Celular</label>
                            <input wire:model.defer="celular" type="text" class="form-control">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="estado_civil_id">Estado Civil</label>
                            <select  wire:model.defer="estado_civil_id" class="form-control">
                                <option value="0">SIN ESPECIFICAR</option>
                                <option value="1">SOLTERO/A</option>
                                <option value="2">CASADO/A</option>
                                <option value="3">DIVORCIADO/A</option>
                                <option value="4">VIUDO/A</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="marca_id_registrar">Marca</label>
                            <select wire:model="marca_id_registrar" class="form-control">
                                @foreach ($marcas as $item)
                                    <option value="{{$item->id}}">{{$item->descripcion}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="modelo_id_registrar">Modelo</label>
                            <select wire:model.defer="modelo_id_registrar" class="form-control">
                                @foreach ($modelos as $item)
                                    <option value="{{$item->id}}">{{$item->descripcion}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="color_id">Color</label>
                            <select wire:model.defer="color_id" class="form-control">
                                @foreach ($colores as $item)
                                    <option value="{{$item->id}}">{{$item->descripcion}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="chapa_registrar">Nro Chapa</label>
                            <input wire:model.defer="chapa_registrar" type="text" class="form-control">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="form-row mb-2">
                        <div class="form-group col-md-3">
                            <label for="plan_id">Plan</label>
                            <select wire:model.defer="plan_id" class="form-control">
                                @foreach ($planes as $plan)
                                    <option value="{{$plan->id}}">{{$plan->descripcion}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-2 col-sm-12">
                            <label for="cantidad">Cantidad</label>
                            <input type="text" wire:model.defer="cantidad"  wire:change="validarCantidad" class="form-control bg-white text-right text-dark">
                        </div>
                        <div class="form-group col-md-2 col-sm-12">
                            <label for="fecha_desde">Fecha Desde</label>
                            <input type="date" wire:model.defer="fecha_desde" wire:blur="validarFechaDesde" class="form-control bg-white text-dark">
                        </div>
                        <div class="form-group col-md-2 col-sm-12">
                            <label for="fecha_hasta">Fecha Hasta</label>
                            <input type="date" wire:model.defer="fecha_hasta" class="form-control bg-white text-dark" disabled>
                        </div>
                    </div>
                   <button
                        type="button"
                        wire:click="guardar"
                        :disabled="$wire.procesando"
                        class="btn btn-success"
                    >
                        <span wire:loading.remove wire:target="guardar">Aplicar Plan</span>
                        <span wire:loading wire:target="guardar">Procesando...</span>
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>
