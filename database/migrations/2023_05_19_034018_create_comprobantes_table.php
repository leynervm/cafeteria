<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComprobantesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comprobantes', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date');
            $table->dateTime('expire');
            $table->integer('correlativo');
            $table->string('codeserie', 4);
            $table->string('seriecompleta', 13);
            $table->decimal('gravado', 10, 2);
            $table->decimal('exonerado', 10, 2);
            $table->decimal('descuento', 10, 2);
            $table->decimal('igv', 10, 2);
            $table->decimal('total', 10, 2);
            $table->decimal('otros', 10, 2);
            $table->string('direccion', 255);
            $table->text('leyenda');
            $table->string('payment', 7);
            $table->string('hash', 32)->nullable();
            $table->string('codesunat', 4)->nullable();
            $table->text('descripcionsunat')->nullable();
            $table->string('moneda', 4)->default('PEN');
            $table->integer('percent')->default(18);
            $table->integer('status')->default(0);
            $table->string('referencia', 13)->nullable();
            $table->unsignedBigInteger('formapago_id')->nullable();
            $table->unsignedBigInteger('coupon_id')->nullable();
            $table->unsignedBigInteger('client_id')->nullable();
            $table->unsignedBigInteger('serie_id')->nullable();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('empresa_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();

            $table->foreign('formapago_id')->on('formapagos')->references('id');
            $table->foreign('coupon_id')->on('coupons')->references('id');
            $table->foreign('client_id')->on('clients')->references('id');
            $table->foreign('serie_id')->on('series')->references('id');
            $table->foreign('order_id')->on('orders')->references('id');
            $table->foreign('empresa_id')->on('empresas')->references('id');
            $table->foreign('user_id')->on('users')->references('id');
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
        Schema::dropIfExists('comprobantes');
    }
}
