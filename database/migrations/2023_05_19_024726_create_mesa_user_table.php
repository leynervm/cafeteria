<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMesaUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mesa_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mesa_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('mesa_id')->on('mesas')->references('id');
            $table->foreign('user_id')->on('users')->references('id');
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
        Schema::dropIfExists('mesa_user');
    }
}
