<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGamesessionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gamesession', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('GameId');
            $table->foreign('GameId')->references('id')->on('games')->onDelete('cascade');
            $table->unsignedBigInteger('UserId');
            $table->foreign('UserId')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('gamesession');
    }
}
