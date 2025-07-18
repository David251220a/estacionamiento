<?php

namespace App\Http\Controllers;

use App\Models\Sifen;
use Illuminate\Http\Request;
use App\Services\SifenServices;

class SifenController extends Controller
{
    public $sifen;

    public function __construct()
    {
        $this->sifen = new SifenServices();
    }

    public function enviar(Sifen $sifen)
    {
        return $sifen;
    }
}
