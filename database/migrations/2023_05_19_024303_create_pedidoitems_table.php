<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePedidoitemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedidoitems', function (Blueprint $table) {
            $table->id();
            $table->integer('cantidad');
            $table->decimal('price', 8, 2);
            $table->decimal('importe', 8, 2);
            $table->integer('status')->default(0);
            $table->unsignedBigInteger('agregado_id')->nullable();
            $table->unsignedBigInteger('pedido_id')->nullable();
            $table->foreign('agregado_id')->on('agregados')->references('id');
            $table->foreign('pedido_id')->on('pedidos')->references('id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pedidoitems');
    }
}
