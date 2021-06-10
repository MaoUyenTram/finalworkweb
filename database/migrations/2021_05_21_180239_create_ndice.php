<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNdice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ndice', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('GameId');
            $table->foreign('GameId')->references('id')->on('games')->onDelete('cascade');
            $table->integer('n');
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
        Schema::dropIfExists('ndice');
    }
}
