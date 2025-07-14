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
        Schema::create('sifens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('factura_id')->constrained();
            $table->string('cdc', 250)->unique();
            $table->integer('tipo_doc')->default(1);
            $table->string('documento_xml', 250);
            $table->string('documento_pdf', 250);
            $table->string('zipeado');
            $table->unsignedBigInteger('secuencia');
            $table->unsignedBigInteger('sifen_num_transaccion');
            $table->string('sifen_estado', 250);
            $table->text('sifen_mensaje')->nullable();
            $table->dateTime('fecha_firma');
            $table->text('link_qr');
            $table->string('evento')->nullable();
            $table->unsignedBigInteger('sifen_cod');
            $table->integer('tipo_transaccion');
            $table->string('moneda')->default('PYG');
            $table->string('correo_enviado')->default('N');
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
        Schema::dropIfExists('sifens');
    }
};
