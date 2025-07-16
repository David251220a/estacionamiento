@extends('layouts.admin')

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/elements/alert.css')}}">
    <link href="{{asset('assets/css/elements/infobox.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

    @include('varios.mensaje')
    
    <div class="row">
        <h4> Factura</h4>
        <a href="#" class="btn btn-success">Crear Xml</a>
    </div>
    

@endsection


@section('js')
    <script src="{{asset('js/registro.js')}}"></script>
@endsection