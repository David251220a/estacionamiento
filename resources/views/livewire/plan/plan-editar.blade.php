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
                        Actualizar Plan Persona
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="form-row mb-2">
                        <div class="form-group col-md-3">
                            <label for="documento_crear">Documento</label>
                            <input type="text" class="form-control text-right text-dark" value="{{number_format($persona->documento, 0, ".", ".")}}" readonly>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="nombre">Nombre</label>
                            <input type="text" class="form-control text-dark" value="{{$persona->nombre}}" readonly>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="apellido">Apellido</label>
                            <input  type="text" class="form-control text-dark" value="{{$persona->apellido}}" readonly>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="apellido">Estado</label>
                            <select wire:model.defer="estado_id" class="form-control">
                                <option value="1">ACTIVO</option>
                                <option value="2">INACTIVO</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="form-row mb-2">
                        <div class="form-group col-md-3">
                            <label for="plan_id">Plan</label>
                            <input type="text" class="form-control bg-white text-right text-dark" value="{{$plan_persona->plan->descripcion}}" disabled>
                        </div>
                        <div class="form-group col-md-2 col-sm-12">
                            <label for="cantidad">Cantidad</label>
                            <input type="text" class="form-control bg-white text-right text-dark" value="{{$plan_persona->cantidad}}" disabled>
                        </div>
                        <div class="form-group col-md-2 col-sm-12">
                            <label for="fecha_desde">Fecha Desde</label>
                            <input type="date" class="form-control bg-white text-dark" value="{{$plan_persona->fecha_inicio}}" disabled>
                        </div>
                        <div class="form-group col-md-2 col-sm-12">
                            <label for="fecha_fin">Fecha Hasta</label>
                            <input type="date" wire:model.defer="fecha_fin" class="form-control bg-white text-dark" disabled>
                        </div>
                        <div class="form-group col-md-2 col-sm-12">
                            <label for="recalculo" class="" style="width: 100%">Recalcular Fecha</label>
                            <button type="button" wire:click="calcular" class="btn btn-info">
                                <i class="fas fa-circle-notch"></i>
                            </button>
                        </div>
                    </div>
                   <button
                        type="button"
                        wire:click="guardar"
                        :disabled="$wire.procesando"
                        class="btn btn-success"
                    >
                        <span wire:loading.remove wire:target="guardar">Actualizar</span>
                        <span wire:loading wire:target="guardar">Procesando...</span>
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>
