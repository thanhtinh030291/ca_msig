<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReasonRejectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reason_reject', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 500);
            $table->integer('term_id')->nullable();
            $table->longText('template');
            $table->integer('created_user');
            $table->integer('updated_user');
            $table->timestamps();
            $table->integer('is_deleted')->default('0');
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
        Schema::dropIfExists('reason_reject');
    }
}
