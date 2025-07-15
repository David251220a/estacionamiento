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
                        {{$titulo}}
                    </div>
                </div>
            </div>

            <div class="row" style="display: {{$ver_documento_search}}">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="form-row mb-2">
                        <div class="form-group col-md-3">
                            <label for="documento_search">Documento</label>
                            <input wire:model.defer="documento_search" type="text" class="form-control" placeholder="Documento" onkeyup="punto_decimal(this)">
                        </div>
                    </div>
                    <button type="button" wire:click="buscar_persona()" class="btn btn-primary">Siguiente</button>
                </div>
            </div>

            <div class="row" style="display: {{$ver_crear_persona}}">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="form-row mb-2">
                        <div class="form-group col-md-3">
                            <label for="documento_crear">Documento</label>
                            <input wire:model.defer="documento_crear" type="text" class="form-control" placeholder="Documento" readonly>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="nombre_crear">Nombre</label>
                            <input wire:model.defer="nombre_crear" type="text" class="form-control" placeholder="Nombre">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="apellido_crear">Apellido</label>
                            <input wire:model.defer="apellido_crear" type="text" class="form-control" placeholder="Apellido">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="ruc_crear">RUC</label>
                            <input wire:model.defer="ruc_crear" type="text" class="form-control" placeholder="RUC">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="email_crear">Correo</label>
                            <input wire:model.defer="email_crear" type="text" class="form-control" placeholder="Correo">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="fecha_nac_crear">Fecha Nacimiento</label>
                            <input wire:model.defer="fecha_nac_crear" type="date" class="form-control">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="sexo_crear">Sexo</label>
                            <select  wire:model.defer="sexo_crear" class="form-control">
                                @foreach ($sexo as $item)
                                    <option value="{{$item->id}}">{{$item->descripcion}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="celular_crear">Celular</label>
                            <input wire:model.defer="celular_crear" type="text" class="form-control">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="estado_civil_crear">Estado Civil</label>
                            <select  wire:model.defer="estado_civil_crear" class="form-control">
                                <option value="0">SIN ESPECIFICAR</option>
                                <option value="1">SOLTERO/A</option>
                                <option value="2">CASADO/A</option>
                                <option value="3">DIVORCIADO/A</option>
                                <option value="4">VIUDO/A</option>
                            </select>
                        </div>
                    </div>
                    <button type="button" wire:click="crear_persona()" class="btn btn-primary">Siguiente</button>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12" style="display: {{$ver_registrar}}">
                    <div class="form-row mb-2">
                        <div class="form-group col-md-6">
                            <label for="persona_registrar">Documento</label>
                            <input wire:model.defer="persona_registrar" type="text" class="form-control" readonly>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="plan_id_registrar">Plan</label>
                            <select wire:model.defer="plan_id_registrar" class="form-control">
                                @foreach ($planes as $plan)
                                    <option value="{{$plan->id}}">{{$plan->descripcion}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="hora_ingreso">Hora Ingreso</label>
                            <input wire:model.defer="hora_ingreso" type="time" class="form-control" readonly>
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
                            <label for="color_id_registrar">Color</label>
                            <select wire:model.defer="color_id_registrar" class="form-control">
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
                   <button
                        type="button"
                        wire:click="registrar_diario"
                        :disabled="$wire.procesando"
                        class="btn btn-success"
                    >
                        <span wire:loading.remove wire:target="registrar_diario">Finalizar</span>
                        <span wire:loading wire:target="registrar_diario">Procesando...</span>
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>
