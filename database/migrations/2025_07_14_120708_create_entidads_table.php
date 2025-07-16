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
        Schema::create('entidads', function (Blueprint $table) {
            $table->id();
            $table->string('razon_social', 250);
            $table->string('nombra_fantasia', 250);
            $table->string('ruc');
            $table->integer('tipo_contribuyente');
            $table->integer('tipo_regimen')->nullable();
            $table->string('email');
            $table->foreignId('tipo_transaccion_id')->constrained();
            $table->tinyInteger('ambiente')->default(0);
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
        Schema::dropIfExists('entidads');
    }
};
