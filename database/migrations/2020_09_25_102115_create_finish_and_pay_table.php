<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinishAndPayTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('finish_and_pay', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('cl_no')->nullable();
            $table->integer('claim_id');
            $table->string('mantis_id')->nullable();
            $table->string('approve_amt')->nullable();
            $table->integer('finished')->default('0');
            $table->integer('payed')->default('0');
            $table->integer('user')->default('1');
            $table->string('notify')->default('1');
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
        Schema::dropIfExists('finish_and_pay');
    }
}
