<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCdice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cdice', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('GameId');
            $table->foreign('GameId')->references('id')->on('games')->onDelete('cascade');
            $table->string('name');
            $table->integer('weight');
            $table->integer('diceId');
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
        Schema::dropIfExists('cdice');
    }
}
