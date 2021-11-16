<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateReportAdminTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_admin', function (Blueprint $table) {
            $table->increments('id');
            $table->string('MEMB_NAME')->nullable();
            $table->string('POCY_REF_NO')->nullable();
            $table->string('MEMB_REF_NO')->nullable();
            $table->integer('PRES_AMT')->default('0');
            $table->string('INV_NO')->nullable();
            $table->string('PROV_NAME')->nullable();
            $table->date('RECEIVE_DATE');
            $table->integer('REQUEST_SEND')->default('0');
            $table->date('SEND_DLVN_DATE')->nullable();
            $table->integer('created_user');
            $table->integer('updated_user');
            $table->integer('is_deleted')->default('0');
            $table->string('CL_NO')->nullable();
            $table->integer('claim_id');
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
        Schema::drop('report_admin');
    }
}
