<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
        <title>CJPPM | Caja de Jubilaciones y Pensional </title>
        <link rel="icon" type="image/x-icon" href="{{public_path('storage/logo_titulo.png')}}"/>
        <link rel="stylesheet" href="{{ public_path('css/ticket.css') }}">
    </head>

    <body>

        <div class="cont-cabezera">
            <img class="img-cabezera" src="{{ public_path('storage/logo_caja.png')}}" alt="">
        </div>

        <div>
            <h3>Ticket de Estacionamiento</h3>
        </div>

        <div style="margin-top: 10px">
            <table>
                <tbody>
                    <tr>
                        <td>Fecha: {{date('d/m/Y', StrToTime($registro_diario->fecha))}} | {{$factura->plan->descripcion}}</td>
                    </tr>
                    <tr>
                        <td>Hora Entrada: {{$registro_diario->hora_ingreso}}</td>
                    </tr>
                    <tr>
                        <td>Hora Salida: {{$registro_diario->hora_salida}}</td>
                    </tr>
                    @if ($factura->plan_id == 1)
                        <tr>
                            <td>Cant.Hora: {{$factura->facturaDetalle[0]->cantidad}} Hora</td>
                        </tr>
                    @else
                        <tr>
                            <td>
                                Fecha Desde {{date('d/m/Y', StrToTime($factura->PersonaPlan->fecha_inicio))}}
                            </td>
                        </tr>

                        <tr>
                            <td>
                                Fecha Hasta {{date('d/m/Y', StrToTime($factura->PersonaPlan->fecha_fin))}}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Cant. {{$factura->plan->descripcion}}: {{$factura->PersonaPlan->cantidad}}
                            </td>
                        </tr>
                    @endif

                    <tr>
                        <td>Total a Pagar: <b>{{number_format($factura->monto_total, 0, ".", ".")}}</b></td>
                    </tr>
                    <tr>
                        <td>Total abonado: <b>{{number_format($factura->monto_abonado, 0, ".", ".")}}</b></td>
                    </tr>
                    <tr>
                        <td>Vuelto: <b>{{number_format($factura->monto_devuelto, 0, ".", ".")}}</b></td>
                    </tr>
                </tbody>
            </table>
            <table>
                    <tr>
                        <td colspan="3" style="text-align: center; font-weight: bold">Forma de Pago</td>
                    </tr>
                @foreach ($factura->facturaPago as $item)
                    <tr>
                        <td>{{$item->cobroTipo->descripcion}}</td>
                        @if ($item->cobroTipo->banco_ver == 1)
                            <td>{{$item->banco->descripcion}}</td>
                        @else
                            <td></td>
                        @endif
                        <td>{{number_format($item->monto, 0, ".", ".")}}</td>
                    </tr>
                @endforeach
            </table>
            <table>
                <tbody>
                    <tr>
                        <td style="font-weight: bold; text-align: center; font-size: 25px">
                            Ticket: {{str_pad($registro_diario->ticket, 5, '0', STR_PAD_LEFT)}}/{{$registro_diario->anio}}
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center">
                            <img src="data:image/png;base64,{{ $barcode }}" alt="Código de barras">
                        </td>
                    </tr>
                </tbody>
            </table>

        </div>
    </body>

</html>
