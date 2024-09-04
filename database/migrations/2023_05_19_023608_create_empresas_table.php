<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpresasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->string('ruc', 11);
            $table->string('name', 255);
            $table->string('direccion', 255);
            $table->string('zona', 255)->nullable();
            $table->string('urbanizacion', 255)->nullable();
            $table->string('ubigeo', 6);
            $table->string('distrito', 50);
            $table->string('provincia', 50);
            $table->string('departamento', 50);
            $table->string('estado', 20)->nullable();
            $table->string('condicion', 20)->nullable();
            $table->string('logo', 100)->nullable();
            $table->string('icono', 100)->nullable();
            $table->string('publickey', 50)->nullable();
            $table->string('privatekey', 50)->nullable();
            $table->string('usuariosol', 50)->nullable();
            $table->string('clavesol', 50)->nullable();
            $table->string('moneda', 5)->default('PEN');
            $table->integer('default')->default(0);
            $table->softDeletes();
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
        Schema::dropIfExists('empresas');
    }
}
