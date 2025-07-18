<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\RegistroDiarioController;
use App\Http\Controllers\SifenController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/logout', [LoginController::class, 'logout']);

Auth::routes();

Route::group([
    'middleware' => 'auth',
], function(){
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::get('/registro-diario', [RegistroDiarioController::class, 'index'])->name('registro.index');
    Route::get('/registro-diario/create', [RegistroDiarioController::class, 'create'])->name('registro.create');
    Route::get('/registro-diario/{registro_diario}/pagar-tarifa', [RegistroDiarioController::class, 'pagar_tarifa'])->name('registro.pagar_tarifa');

    Route::get('/factura/{factura}/ver', [FacturaController::class, 'show'])->name('factura.show');

    Route::post('/sifen/{sifen}/enviar', [SifenController::class, 'enviar'])->name('sifen.enviar');

});
