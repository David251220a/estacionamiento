<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facturas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('persona_id')->constrained();
            $table->foreignId('plan_id')->constrained();
            $table->foreignId('vehiculo_id')->constrained();
            $table->foreignId('timbrado_id')->constrained();
            $table->integer('numero_factura')->default(0);
            $table->bigInteger('plan_persona')->default(1);
            $table->date('fecha_factura');
            $table->decimal('monto_total', 12, 0)->default(0);
            $table->decimal('monto_abonado', 12, 0)->default(0);
            $table->decimal('monto_devuelto', 12, 0)->default(0);
            $table->foreignId('estado_id')->constrained();
            $table->date('fecha_anulado')->nullable();
            $table->foreignId('user_id')->constrained();
            $table->unsignedBigInteger('usuario_anulacion')->nullable();
            $table->string('motivo_anulacion', 250)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('facturas');
    }
};
