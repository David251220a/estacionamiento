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
        Schema::create('timbrados', function (Blueprint $table) {
            $table->id();
            $table->string('timbrado', 100);
            $table->date('fecha_inicio');
            $table->string('general', 3);
            $table->string('sucursal', 3);
            $table->integer('numero_inicial')->default(0);
            $table->integer('numero_final')->default(0);
            $table->integer('numero_siguiente')->default(0);
            $table->string('codigo_set_id' , 100);
            $table->string('codigo_cliente_set', 250);
            $table->foreignId('estado_id')->constrained();
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
        Schema::dropIfExists('timbrados');
    }
};
