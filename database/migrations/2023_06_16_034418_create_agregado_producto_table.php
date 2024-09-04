<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgregadoProductoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agregado_producto', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agregado_id')->nullable();
            $table->unsignedBigInteger('producto_id')->nullable();
            $table->foreign('agregado_id')->on('agregados')->references('id');
            $table->foreign('producto_id')->on('productos')->references('id');
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
        Schema::dropIfExists('agregado_producto');
    }
}
