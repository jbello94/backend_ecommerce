<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateValorCaracteristicaProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('valor_caracteristica_productos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('caracteristica_producto_id');
            $table->string('valor');
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->foreign('caracteristica_producto_id','caracteristica_producto_id')->references('id')->on('caracteristica_productos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('valor_caracteristica_productos');
    }
}
