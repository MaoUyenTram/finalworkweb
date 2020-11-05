<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePilesItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('piles_items', function (Blueprint $table) {
            $table->unsignedBigInteger('PileId');
            $table->foreign('PileId')->references('id')->on('piles')->onDelete('cascade');
            $table->unsignedBigInteger('ItemId');
            $table->foreign('ItemId')->references('id')->on('items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('piles_items');
    }
}
