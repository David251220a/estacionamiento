<div class="col-lg-12 layout-spacing">
    <div class="statbox widget box box-shadow">
        <div class="widget-content widget-content-area">

            @include('varios.mensaje')

            <h4 class="font-bold mb-3">Consulta de Ingresos</h4>

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 filtered-list-search mx-auto">
                    <div class="form-row mb-2">
                        <div class="form-group col-md-2">
                            <label for="fecha_desde">Fecha Desde</label>
                            <input type="date" wire:model.defer="fecha_desde" class="form-control">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="fecha_hasta">Fecha Hasta</label>
                            <input type="date" wire:model.defer="fecha_hasta" class="form-control">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="forma_cobro">Forma de Cobro</label>
                            <select wire:model.defer = "forma_cobro_id" class="form-control">
                                <option value="99">-- TODOS --</option>
                                @foreach ($formaCobro as $item)
                                    <option value="{{$item->id}}">{{$item->descripcion}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="accion" style="width: 100%">Accion</label>
                            <button type="button" wire:click="buscar" class="btn btn-info">Filtrar</button>
                            <a href="{{route('consulta.pagos_imprimir', [$fecha_desde, $fecha_hasta, $forma_cobro_id])}}" class="btn btn-primary" target="__blank">
                                <i class="fas fa-file-pdf"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div  class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped table-checkable table-highlight-head mb-4">
                            <thead>
                                <tr>
                                    <th class="">Fecha</th>
                                    <th class="">Documento</th>
                                    <th class="">Nombre y Apellido</th>
                                    <th class="">Concepto</th>
                                    <th class="">Plan Pagado</th>
                                    <th class="">Ticket</th>
                                    <th class="">Forma Pago</th>
                                    <th class="">Monto</th>
                                    <th class="text-center">Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $item)
                                    <tr>
                                        <td>{{date('d/m/Y', strtotime($item->fecha_factura))}}</td>
                                        <td>{{number_format($item->persona->documento, 0, ".", ".")}}</td>
                                        <td>{{$item->persona->nombre}} {{$item->persona->apellido}}</td>
                                        <td>{{$item->concepto}}</td>
                                        <td>{{$item->plan->descripcion}}</td>
                                        <td>{{str_pad($item->registroDiario->ticket, 5, '0', STR_PAD_LEFT)}}/{{$item->registroDiario->anio}}</td>
                                        <td>
                                            @foreach ($item->facturaPago as $pago)
                                                {{$pago->cobroTipo->descripcion}}
                                            @endforeach
                                        </td>
                                        <td>{{number_format($item->monto_total, 0, ".", ".")}}</td>
                                        <td>
                                            <button type="button" class="btn btn-danger" onclick="anular({{$item->id}})">Anular</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="7">Total</th>
                                    <th colspan="2">{{number_format($total, 0, ".", ".")}}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    {{ $data->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
