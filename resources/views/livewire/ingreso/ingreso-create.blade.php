<div class="col-lg-12 layout-spacing">
    <div class="statbox widget box box-shadow">
        <div class="widget-content widget-content-area">

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="form-row mb-2">
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
                        wire:click="guardar"
                        :disabled="$wire.procesando"
                        class="btn btn-success"
                    >
                        <span wire:loading.remove wire:target="guardar">Finalizar</span>
                        <span wire:loading wire:target="guardar">Procesando...</span>
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>