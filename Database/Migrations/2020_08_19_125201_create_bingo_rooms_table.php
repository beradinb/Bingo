<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBingoRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bingo_rooms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title', 100);
            $table->string('bingo_type', 4);
            $table->string('image', 200);
            $table->string('room_888_id', 100);
            $table->tinyInteger('status')->default(1);
            $table->integer('category_id')->nullable();
            $table->timestamps();
        });

        Schema::create('bingo_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title', 100);
            $table->string('type', 4);
            $table->string('category_888_id', 100);
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
        Schema::dropIfExists('bingo_rooms');
        Schema::dropIfExists('bingo_categories');
    }
}
