@extends('layouts.admin')

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/elements/alert.css')}}">
    <link href="{{asset('assets/css/elements/infobox.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

    @include('varios.mensaje')

    <div class="row">
        <div class="col-md-12">
            <h4> Factura</h4>
            <br>
            <h4>{{$sifen->cdc}}</h4>
            <h4>Sifen Mensaje: {{$sifen->sifen_mensaje}}</h4>
            <a href="#" class="btn btn-success">Crear Xml</a>
            <form action="{{route('sifen.enviar', $sifen)}}" method="post">
                @csrf
                <button type="submit" class="btn btn-success">Enviar Sifen</button>
            </form>
        </div>
    </div>


@endsection


@section('js')
    <script src="{{asset('js/registro.js')}}"></script>
@endsection
