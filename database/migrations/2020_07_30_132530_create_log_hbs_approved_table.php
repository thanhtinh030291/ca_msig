<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogHbsApprovedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_hbs_approved', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('cl_no')->nullable();
            $table->integer('export_letter_id')->nullable();
            $table->longText('approve')->nullable();
            $table->longText('hbs')->nullable();
            $table->string('MANTIS_ID')->nullable();
            $table->string('MEMB_NAME')->nullable();
            $table->string('POCY_REF_NO')->nullable();
            $table->string('MEMB_REF_NO')->nullable();
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
        Schema::dropIfExists('log_hbs_approved');
    }
}
