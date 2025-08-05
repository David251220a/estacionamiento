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
                        <td>Fecha: {{$registro_diario->fecha}} | {{$plan->descripcion}}</td>
                    </tr>

                    <tr>
                        <td>
                            Fecha Desde {{date('d/m/Y', StrToTime($PersonaPlan->fecha_inicio))}}
                        </td>
                    </tr>

                    <tr>
                        <td>
                            Fecha Hasta {{date('d/m/Y', StrToTime($PersonaPlan->fecha_fin))}}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Cant. {{$plan->descripcion}}: {{$PersonaPlan->cantidad}}
                        </td>
                    </tr>
                </tbody>
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
