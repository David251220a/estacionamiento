<?php

namespace App\Http\Controllers;

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
}
