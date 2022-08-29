<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNegocioDetallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('negocio_detalles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('negocio_id');
            $table->unsignedBigInteger('caracteristica_id');
            $table->string('valor', 1024);

            $table->foreign('negocio_id')->references('id')->on('negocios');
            $table->foreign('caracteristica_id')->references('id')->on('caracteristica_negocios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('negocio_detalles');
    }
}
