<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComprobanteitemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comprobanteitems', function (Blueprint $table) {
            $table->id();
            $table->integer('item');
            $table->text('descripcion');
            $table->string('unit', 4);
            $table->string('code', 5);
            $table->decimal('cantidad', 10, 2);
            $table->decimal('price', 10, 2);
            $table->decimal('igv', 10, 2);
            $table->decimal('importe', 10, 2);
            $table->unsignedBigInteger('pedido_id')->nullable();
            $table->unsignedBigInteger('comprobante_id')->nullable();

            $table->foreign('pedido_id')->on('pedidos')->references('id');
            $table->foreign('comprobante_id')->on('comprobantes')->references('id');
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
        Schema::dropIfExists('comprobanteitems');
    }
}
