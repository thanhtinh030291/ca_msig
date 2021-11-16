<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateClaimWordSheetTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('claim_word_sheet', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('claim_id');
            $table->string('mem_ref_no');
            $table->longText('visit')->nullable();
            $table->longText('assessment')->nullable();
            $table->longText('medical')->nullable();
            $table->integer('claim_resuft')->nullable();
            $table->longText('benefit')->nullable();
            $table->bigInteger('claim_amt')->default('0');
            $table->bigInteger('payable_amt')->default('0');
            $table->text('note')->nullable();
            $table->integer('notification')->default('0');
            $table->integer('dischage_summary')->default('0');
            $table->integer('vat')->default('0');
            $table->integer('copy_of')->default('0');
            $table->integer('medical_report')->default('0');
            $table->integer('breakdown')->default('0');
            $table->integer('discharge_letter')->default('0');
            $table->integer('treatment_plant')->default('0');
            $table->integer('incident_report')->default('0');
            $table->integer('prescription')->default('0');
            $table->integer('lab_test')->default('0');
            $table->integer('police_report')->default('0');
            $table->integer('created_user');
            $table->integer('updated_user');
            $table->integer('is_deleted')->default('0');
            $table->longText('request_qa')->nullable();
            $table->integer('status')->default('0');
            $table->integer('old_number_page_send')->default('0');

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
        Schema::drop('claim_word_sheet');
    }
}
