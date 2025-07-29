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

    public function enviar_evento(Request $request, Sifen $sifen)
    {
        $tipo = $request->tipo;
        $motivo = 'Prueba para anulacion';

        if($tipo == 1){
            $xml = $this->sifen->inutizacion($sifen, $motivo);
        }

        if($tipo == 2){
            $xml = $this->sifen->cancelacion($sifen, $motivo);
        }

        if($tipo == 3){
            $xml = $this->sifen->nominacion($sifen);
        }

        $response = $this->sifen->envioEvento($sifen, $xml, 400000, 2);

        return $response;
    }

    public function enviar_sifen(Sifen $sifen)
    {
        //$ruta_zip = $this->sifen->lotear($sifen);
        //$sifen->update([
        //    'documento_zip' => $ruta_zip,
        //]);
        //return $this->sifen->enviar_zip($sifen);
        return $this->sifen->consultar($sifen);
        //return $this->sifen->consultar_cdc($sifen);
        return $sifen;
    }

}
