<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProviderTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('provider', function (Blueprint $table) {
            $table->increments('id');
            $table->string('PROV_CODE')->unique();
            $table->date('EFF_DATE')->nullable();
            $table->date('TERM_DATE')->nullable();
            $table->string('PROV_NAME')->nullable();
            $table->string('ADDR')->nullable();
            $table->string('SCMA_OID_COUNTRY')->nullable();
            $table->string('PAYEE')->nullable();
            $table->string('BANK_NAME')->nullable();
            $table->string('CL_PAY_ACCT_NO')->nullable();
            $table->string('BANK_ADDR')->nullable();
            $table->integer('is_deleted')->default('0');
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
        Schema::drop('provider');
    }
}
