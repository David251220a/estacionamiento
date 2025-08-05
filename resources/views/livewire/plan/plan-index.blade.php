<div class="col-lg-12 layout-spacing">
    <div class="statbox widget box box-shadow">
        <div class="widget-content widget-content-area">

            @include('varios.mensaje')

            <h4 class="font-bold mb-3">Personas con Plan Activos</h4>

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
                <div  class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped table-checkable table-highlight-head mb-4">
                            <thead>
                                <tr>
                                    <th class="">Documento</th>
                                    <th class="">Nombre y Apellido</th>
                                    <th class="">Plan</th>
                                    <th class="">Fecha Inicio</th>
                                    <th class="">Fecha Fin</th>
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
                                            {{$item->plan->descripcion}}
                                        </td>
                                        <td>
                                            {{date('d/m/Y', strtotime($item->fecha_inicio))}}
                                        </td>
                                        <td>
                                            {{date('d/m/Y', strtotime($item->fecha_fin))}}
                                        </td>
                                        <td class="text-center">
                                            <ul class="table-controls">
                                                <li class="mr-2">
                                                    <a href="{{route('planpersona.edit', $item)}}" data-toggle="tooltip" data-placement="top" title="Cobrar">
                                                        <i class="fas fa-pencil" style="color: green; font-size: 23px"></i>
                                                    </a>
                                                </li>
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
