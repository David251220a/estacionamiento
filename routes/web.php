<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ConsultaController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\Limpiar;
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

Route::get('/limpiar', [Limpiar::class, 'limpiar'])->name('limpiar');
Route::get('/crear/acceso', [Limpiar::class, 'acceso'])->name('acceso');

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/logout', [LoginController::class, 'logout']);

Auth::routes();

Route::group([
    'middleware' => 'auth',
], function(){
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/registro-diario', [RegistroDiarioController::class, 'index'])->name('registro.index');
    Route::get('/registro-diario/create', [RegistroDiarioController::class, 'create'])->name('registro.create');
    Route::get('/registro-diario/{registro_diario}/show', [RegistroDiarioController::class, 'show'])->name('registro.show');
    Route::get('/registro-diario/{registro_diario}/imprimr', [RegistroDiarioController::class, 'imprimir'])->name('registro.imprimir');
    Route::get('/registro-diario/{registro_diario}/pagar-tarifa', [RegistroDiarioController::class, 'pagar_tarifa'])->name('registro.pagar_tarifa');
    Route::get('/generar-qr/{id}', [RegistroDiarioController::class, 'generarQr'])->name('generar.qr');

    Route::get('/factura/{factura}/ver', [FacturaController::class, 'show'])->name('factura.show');
    Route::get('/factura/{factura}/imprimir', [FacturaController::class, 'imprimir'])->name('factura.imprimir');
    Route::post('/sifen/{sifen}/enviar', [SifenController::class, 'enviar_evento'])->name('sifen.enviar_evento');
    Route::post('/sifen/{sifen}/enviar_sifen', [SifenController::class, 'enviar_sifen'])->name('sifen.enviar_sifen');


    Route::get('/consulta/pagos', [ConsultaController::class, 'pagos'])->name('consulta.pagos');
    Route::get('/consulta/reporte/{fechaDesde}/{fechaHasta}/{formaCobro}/imprimir', [ConsultaController::class, 'pagos_imprimir'])->name('consulta.pagos_imprimir');

});
