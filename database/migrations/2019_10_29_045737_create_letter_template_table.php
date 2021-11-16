<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLetterTemplateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('letter_template', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->longText('template');
            $table->integer('is_deleted')->default('0');
            $table->integer('created_user');
            $table->integer('updated_user');
            $table->softDeletes();
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
        Schema::dropIfExists('letter_template');
    }
}
