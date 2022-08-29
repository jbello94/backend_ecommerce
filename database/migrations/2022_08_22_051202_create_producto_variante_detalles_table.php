<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductoVarianteDetallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('producto_variante_detalles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('producto_variante_id');
            $table->unsignedBigInteger('valor_caracteristica_producto_id');
            $table->timestamps();

            $table->foreign('producto_variante_id','producto_variante_id')->references('id')->on('producto_variantes');
            $table->foreign('valor_caracteristica_producto_id','valor_caracteristica_producto_id')->references('id')->on('valor_caracteristica_productos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('producto_variante_detalles');
    }
}
