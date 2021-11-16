<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_history', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('CL_NO')->nullable();
            $table->string('MEMB_NAME')->nullable();
            $table->string('POCY_REF_NO')->nullable();
            $table->string('MEMB_REF_NO')->nullable();
            $table->string('PRES_AMT')->nullable();
            $table->string('APP_AMT')->nullable();
            $table->string('TF_AMT')->nullable();
            $table->string('DEDUCT_AMT')->nullable();
            $table->string('PAYMENT_METHOD')->nullable();
            $table->string('MANTIS_ID')->nullable();

            
            $table->string('ACCT_NAME')->nullable();
            $table->string('ACCT_NO')->nullable();
            $table->string('BANK_NAME')->nullable();
            $table->string('BANK_CITY')->nullable();
            $table->string('BANK_BRANCH')->nullable();
            $table->string('BENEFICIARY_NAME')->nullable();
            $table->string('PP_DATE')->nullable();
            $table->string('PP_PLACE')->nullable();
            $table->string('PP_NO')->nullable();
            $table->string('CL_TYPE')->nullable();
            $table->string('BEN_TYPE')->nullable();

            
            $table->string('PAYMENT_TIME')->nullable();
            $table->string('TF_STATUS')->nullable();
            $table->date('TF_DATE')->nullable();
            $table->string('VCB_SEQ')->nullable();
            $table->string('VCB_CODE')->nullable();
            $table->integer('PAYM_ID')->unique();
            
            $table->string('claim_id')->nullable();
            $table->longText('HBS')->nullable();
            $table->string('url_letter')->nullable();
            $table->string('url_payment')->nullable();
            $table->string('url_unc')->nullable();

            $table->string('update_file')->default('0');
            $table->string('update_hbs')->default('0');

            $table->integer('notify_renew')->default('0');
            $table->text('reason_renew')->nullable();

            $table->integer('created_user');
            $table->integer('updated_user');
            $table->integer('is_deleted')->default('0');
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
        Schema::dropIfExists('payment_history');
    }
}
