@extends('layouts.admin')

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/elements/alert.css')}}">
    <link href="{{asset('assets/css/elements/infobox.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

    @livewire('registro.registro-create')

@endsection


@section('js')
    <script src="{{asset('js/registro.js')}}"></script>
@endsection