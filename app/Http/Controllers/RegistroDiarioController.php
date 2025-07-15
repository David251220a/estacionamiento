<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\RegistroDiario;
use Illuminate\Http\Request;

class RegistroDiarioController extends Controller
{
    public function index()
    {
        return view('registro.index');
    }

    public function create()
    {
        return view('registro.create');
    }

    public function pagar_tarifa(RegistroDiario $registro_diario)
    {
        return view('registro.pagar', compact('registro_diario'));
    }
}
