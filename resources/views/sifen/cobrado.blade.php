@extends('layouts.admin')

@section('styles')
    <link href="{{asset('assets/css/components/cards/card.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<div class="col-lg-12 layout-spacing">
    <div class="statbox widget box box-shadow">
        <div class="widget-content widget-content-area">

            <h4 class="font-bold mb-3">TICKET COBRADO</h4>

            <div class="col-lg-12 col-md-12 col-sm-12">

                <div class="widget-content widget-content-area">

                    <div class="card component-card_4">
                        <div class="card-body">
                            <div class="user-profile">
                                <img src="{{Storage::url('logo_titulo.png')}}" class="" alt="...">
                            </div>
                            <div class="user-info">
                                <h5 class="card-user_name">{{str_pad($registro_diario->ticket, 5, '0', STR_PAD_LEFT)}}/{{$registro_diario->anio}}</h5>
                                <h6>Persona : {{number_format($factura->persona->documento, 0, ".", ".")}} - {{$factura->persona->nombre}} {{$factura->persona->apellido}}</h6>
                                <p class="card-user_occupation">Hora Entrada</p>
                                <div class="card-star_rating">
                                    <span class="badge badge-primary">{{$registro_diario->hora_ingreso}}</span>
                                </div>
                                <p class="card-user_occupation">Hora Salida</p>
                                <div class="card-star_rating">
                                    <span class="badge badge-primary">{{$registro_diario->hora_salida}}</span>
                                </div>
                                <p class="card-text">
                                    Cant. Hora: {{$factura->facturaDetalle[0]->cantidad}} <br>
                                    Plan eligido: {{$registro_diario->plan->descripcion}} <br>
                                    Total a Pagar: <b>{{number_format($factura->monto_total, 0, ".", ".")}} <br>
                                    Total abonado: <b>{{number_format($factura->monto_abonado, 0, ".", ".")}} <br>
                                    Vuelto: <b>{{number_format($factura->monto_devuelto, 0, ".", ".")}}
                                </p>
                                <a href="{{route('factura.imprimir', $factura)}}" class="btn btn-info" target="__blank">Imprimir</a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>


@endsection

@section('js')
    <script src="{{asset('js/factura.js')}}"></script>
@endsection
