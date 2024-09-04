<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetallenotasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detallenotas', function (Blueprint $table) {
            $table->id();
            $table->integer('cantidad')->default(1);
            $table->decimal('price', 8, 2);
            $table->decimal('igv', 8, 2)->default(0);
            $table->unsignedBigInteger('agregado_id')->nullable();
            $table->unsignedBigInteger('notapedido_id')->nullable();
            $table->foreign('agregado_id')->on('agregados')->references('id');
            $table->foreign('notapedido_id')->on('notapedidos')->references('id')->cascadeOnDelete()->cascadeOnUpdate();
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
        Schema::dropIfExists('detallenotas');
    }
}
