<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotapedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notapedidos', function (Blueprint $table) {
            $table->id();
            $table->integer('cantidad');
            $table->decimal('price', 8, 2);
            $table->decimal('igv', 8, 2)->default(0);
            $table->string('detalle', 255)->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('producto_id')->nullable();
            $table->foreign('user_id')->on('users')->references('id');
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
        Schema::dropIfExists('notapedidos');
    }
}
