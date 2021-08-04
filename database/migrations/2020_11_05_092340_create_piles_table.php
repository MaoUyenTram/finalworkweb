<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('piles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('GameId');
            $table->foreign('GameId')->references('id')->on('games') ->onDelete('cascade');
            $table->string('name');
            $table->boolean('private')->nullable();
            $table->tinyInteger('visibility');
            $table->string('type')->default('stack');
            $table->string('image')->nullable();
            $table->string('owner')->nullable();
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
        Schema::dropIfExists('piles');
    }
}
