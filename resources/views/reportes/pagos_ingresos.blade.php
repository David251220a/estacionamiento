<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
        <title>CJPPM | Caja de Jubilaciones y Pensional </title>
        <link rel="icon" type="image/x-icon" href="{{public_path('storage/logo_titulo.png')}}"/>
        <link rel="stylesheet" href="{{ public_path('css/pagos.css') }}">
    </head>

    <body>

        <div>

            <table style="margin-bottom: 16px; width: 100%">
                <tr>
                    <td>
                        <img class="img-cabezera" src="{{ public_path('storage/logo_caja.png')}}" alt="" height="50">
                    </td>
                    <td style="text-align: center; font-weight:bold; font-size: 15px">

                    </td>
                </tr>
            </table>

            <div class="title" style="margin-bottom: 0px">Consulta de Cobro Diario</div>
            <table style="width:100%; border-collapse: separate; border: 1px solid #009c48; padding: 1px;">
                <tr style="margin: 0px; padding: 0px">
                    <td>Forma de Cobro: <b>{{$titulo_cobro}}</b></td>
                    <td>Fecha Desde: <b>{{date('d/m/Y', StrToTime($fechaDesde))}}</b></td>
                    <td>Fecha Hasta: <b>{{date('d/m/Y', StrToTime($fechaHasta))}}</b></td>
                </tr>
            </table>
            <br>

            <table class="table">
                <thead>
                    <tr>
                        <th class="">#</th>
                        <th class="">Fecha</th>
                        <th class="">Documento</th>
                        <th class="">Nombre y Apellido</th>
                        <th class="">Concepto</th>
                        <th class="">Plan Pagado</th>
                        <th class="">Ticket</th>
                        <th class="">Forma Pago</th>
                        <th class="">Monto</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                        <tr>
                            <td style="text-align: right">{{number_format($loop->iteration, 0, ".", ".")}}</td>
                            <td>{{date('d/m/Y', strtotime($item->fecha_factura))}}</td>
                            <td style="text-align: right">{{number_format($item->persona->documento, 0, ".", ".")}}</td>
                            <td>{{$item->persona->nombre}} {{$item->persona->apellido}}</td>
                            <td>{{$item->concepto}}</td>
                            <td>{{$item->plan->descripcion}}</td>
                            <td>{{str_pad($item->registroDiario->ticket, 5, '0', STR_PAD_LEFT)}}/{{$item->registroDiario->anio}}</td>
                            <td>
                                @foreach ($item->facturaPago as $pago)
                                    {{$pago->cobroTipo->descripcion}}
                                @endforeach
                            </td>
                            <td style="text-align: right">{{number_format($item->monto_total, 0, ".", ".")}}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="8">Total Ingresado</td>
                        <td style="text-align: right">
                            {{number_format($data->sum('monto_total'), 0,',','.')}}
                        </td>
                    </tr>
                </tfoot>
            </table>

            {{-- <br>
            <table class="resumen">
                <thead>
                    <tr>
                        <th colspan="2">Resumen</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Total Aporte Personal</td>
                        <td style="text-align: right">{{number_format($total_aporte, 0,',','.')}}</td>
                    </tr>
                    <tr>
                        <td>Total Aporte Patronal</td>
                        @php
                            $patronal = $data->detalle->sum('monto_aporte') + $data->detalle->sum('monto_bonificacion');
                        @endphp
                        <td style="text-align: right">{{number_format($patronal, 0,',','.')}}</td>
                    </tr>
                    <tr>
                        <td>Total General Aportado</td>
                        <td style="text-align: right">{{number_format($patronal + $total_aporte, 0,',','.')}}</td>
                    </tr>
                </tbody>
            </table> --}}
        </div>

    </body>
</html>
