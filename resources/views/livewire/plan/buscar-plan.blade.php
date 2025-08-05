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
                        Buscar Plan Persona
                    </div>
                </div>
            </div>

            <h4>Ticket: {{str_pad($registro_diario->ticket, 5, '0', STR_PAD_LEFT)}}/{{$registro_diario->anio}}</h4>

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="form-row mb-2">
                        <div class="form-group col-md-3">
                            <label for="documento_crear">Documento</label>
                            <input wire:model.defer="documento" type="text" class="form-control text-dark" placeholder="Documento" onkeyup="punto_decimal(this)"
                            {{ ($editar_documento == false ? 'readonly' : '' )}}>
                        </div>
                        <div class="form-group col-md-3" style="display: {{$verdatos}}">
                            <label for="nombre">Persona</label>
                            <input type="text" class="form-control text-dark" value="{{$nombre}}" disabled>
                        </div>
                        <div class="form-group col-md-3" style="display: {{$verdatos}}">
                            <label for="planAcitvo">Plan</label>
                            <input type="text" class="form-control text-dark" value="{{$planActivo}}" disabled>
                        </div>
                        <div class="form-group col-md-3" style="display: {{$verdatos}}">
                            <label for="fecha_desde">Fecha Desde</label>
                            <input type="date" class="form-control text-dark" value="{{$fechaDesde}}" disabled>
                        </div>
                        <div class="form-group col-md-3" style="display: {{$verdatos}}">
                            <label for="fecha_hasta">Fecha Hasta</label>
                            <input type="date" class="form-control text-dark" value="{{$fechaHasta}}" disabled>
                        </div>
                    </div>

                    <div>
                        <div class="form-row mb-2">
                            <div class="form-group col-md-1" style="display: {{$ver_buscar}}">
                                <button type="button" class="btn btn-primary" wire:click="buscar_persona"> Buscar</button>
                            </div>
                            <div class="form-group col-md-2"  style="display: {{$verdatos}}">
                                <button
                                    type="button"
                                    wire:click="guardar"
                                    :disabled="$wire.procesando"
                                    class="btn btn-success"
                                >
                                    <span wire:loading.remove wire:target="guardar">Finalizar</span>
                                    <span wire:loading wire:target="guardar">Procesando...</span>
                                </button>
                            </div>
                            <div class="form-group col-md-2"  style="display: {{$verdatos}}">
                                <button type="button" class="btn btn-danger" wire:click="cancelar">
                                    Cancelar
                                </button>
                            </div>
                        </div>

                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
