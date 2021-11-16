<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateRoomAndBoardsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('room_and_boards', function (Blueprint $table) {
            $table->increments('id');
            $table->text('name');
            $table->text('code_claim');
            $table->longText('line_rb')->nullable();
            $table->integer('created_user');
            $table->integer('updated_user');
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
        Schema::drop('room_and_boards');
    }
}
