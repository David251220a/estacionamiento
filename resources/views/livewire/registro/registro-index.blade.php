<div class="col-lg-12 layout-spacing">
    <div class="statbox widget box box-shadow">
        <div class="widget-content widget-content-area">

            @include('varios.mensaje')

            <h4 class="font-bold mb-3">Lista de Vehiculos Ingresados</h4>

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 filtered-list-search mx-auto">
                    <form class="form-inline my-2 my-lg-0 justify-content-center" onsubmit="return false;" onkeydown="return event.key != 'Enter';">
                        <div class="w-100">
                            <input type="text" wire:model="search" class="w-100 form-control product-search br-30" placeholder="Buscar..." >
                            <button class="btn btn-primary" type="button">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search">
                                <circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8 col-md-8 col-sm-8 ">
                    <a href="{{route('registro.create')}}" class="btn btn-outline-success mb-2">Agregar</a>
                </div>
            </div>

            <div class="row ">
                <div  class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped table-checkable table-highlight-head mb-4">
                            <thead>
                                <tr>
                                    <th class="">Documento</th>
                                    <th class="">Nombre y Apellido</th>
                                    <th class="">Hora Ingreso</th>
                                    <th class="">Plan Activo</th>
                                    <th class="">Hora Ingreso</th>
                                    <th class="">Tiempo Transcurrido</th>
                                    <th class="">Pagado?</th>
                                    <th class="text-center">Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $item)
                                    <tr>
                                        <td class="text-right">
                                            {{number_format($item->persona->documento, 0, ".", ".")}}
                                        </td>
                                        <td>
                                            {{$item->persona->nombre}} {{$item->persona->apellido}}
                                        </td>
                                        <td>
                                            {{$item->hora_ingreso}}
                                        </td>
                                        <td>
                                            @if ($item->persona->tiene_plan_activo)
                                                <span class="badge badge-success">Sí</span>
                                            @else
                                                <span class="badge badge-secondary">No</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{$item->hora_ingreso}}
                                        </td>
                                        <td>
                                            @php
                                                $horaIngreso = \Carbon\Carbon::createFromFormat('H:i:s', $item->hora_ingreso);
                                                $ahora = \Carbon\Carbon::now();
                                                $diferencia = $horaIngreso->diff($ahora);
                                            @endphp
                                            {{ $diferencia->h }}h {{ $diferencia->i }}m
                                        </td>
                                        <td>
                                            @if ($item->factura == 1)
                                                <span class="badge outline-badge-info shadow-none">Pagado</span>
                                            @else
                                                <span class="badge badge-danger">No Pagado</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <ul class="table-controls">

                                                @if ($item->factura == 0)
                                                    <li class="mr-2">
                                                        <a href="{{route('registro.pagar_tarifa', $item)}}" data-toggle="tooltip" data-placement="top" title="Cobrar">
                                                            <i class="fa-solid fa-cash-register" style="color: green; font-size: 23px"></i>
                                                        </a>
                                                    </li>
                                                @endif

                                                <li class="mr-2">
                                                    <a href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="Imprimir">
                                                        <i class="fa-solid fa-print" style="font-size: 23px"></i>
                                                    </a>
                                                </li>
                                                {{-- <li>
                                                    <a href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="Delete">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 text-danger"><polyline points="3 6 5 6 21 6">
                                                        </polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                        <line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                                    </a>
                                                </li> --}}
                                            </ul>
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
