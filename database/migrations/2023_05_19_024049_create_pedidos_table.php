<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->timestamp('date');
            $table->integer('cantidad');
            $table->decimal('price', 8, 2);
            $table->decimal('igv', 8, 2);
            $table->decimal('otros', 8, 2);
            $table->decimal('importe', 8, 2);
            $table->string('detalle', 255)->nullable();;
            $table->integer('status')->default(0);
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('producto_id')->nullable();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->foreign('user_id')->on('users')->references('id');
            $table->foreign('producto_id')->on('productos')->references('id');
            $table->foreign('order_id')->on('orders')->references('id');
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
        Schema::dropIfExists('pedidos');
    }
}
