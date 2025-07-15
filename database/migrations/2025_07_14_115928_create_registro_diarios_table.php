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
        Schema::create('registro_diarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('persona_id')->constrained();
            $table->foreignId('plan_id')->constrained();
            $table->foreignId('marca_id')->constrained();
            $table->foreignId('modelo_id')->constrained();
            $table->foreignId('color_id')->constrained();
            $table->foreignId('tipo_vehiculo_id')->constrained();
            $table->integer('ticket')->default(0);
            $table->integer('anio')->default(0);
            $table->string('chapa')->nullable();
            $table->date('fecha');
            $table->time('hora_ingreso');
            $table->time('hora_salida')->nullable();
            $table->bigInteger('plan_persona')->default(1);
            $table->tinyInteger('plan_activo')->default(0);
            $table->tinyInteger('facturado')->default(0);
            $table->foreignId('estado_id')->constrained();
            $table->foreignId('user_id')->constrained();
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
        Schema::dropIfExists('registro_diarios');
    }
};
