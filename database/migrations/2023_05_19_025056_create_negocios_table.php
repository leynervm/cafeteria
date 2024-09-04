<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNegociosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('negocios', function (Blueprint $table) {
            $table->id();
            $table->timestamp('date');
            $table->string('ruc', 11);
            $table->string('name', 255);
            $table->string('propietario', 255);
            $table->decimal('precio', 8, 2);
            $table->string('payment');
            $table->timestamp('datedown')->nullable();
            $table->integer('status');
            $table->string('driver');
            $table->string('host');
            $table->string('database');
            $table->string('port');
            $table->string('user');
            $table->string('password');
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
        Schema::dropIfExists('negocios');
    }
}
