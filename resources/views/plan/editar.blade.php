@extends('layouts.admin')

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/elements/alert.css')}}">
    <link href="{{asset('assets/css/elements/infobox.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/css/tables/table-basic.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/css/elements/search.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

    @livewire('plan.plan-editar', ['plan_persona' => $plan_persona], key($plan_persona->id))

@endsection


@section('js')
    <script src="{{asset('js/registro.js')}}"></script>
@endsection
