<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogMfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_mfile', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('claim_id')->nullable();
            $table->string('cl_no')->nullable();
            $table->string('m_errorCode')->nullable();
            $table->string('m_errorMsg')->nullable();
            $table->string('m_policy_holder_id')->nullable();
            $table->string('m_policy_holder_latest_version')->nullable();
            $table->string('m_member_id')->nullable();
            $table->string('m_member_latest_version')->nullable();
            $table->string('m_claim_id')->nullable();
            $table->string('m_claim_latest_version')->nullable();
            $table->string('m_claim_file_id')->nullable();
            $table->string('m_claim_file_latest_version')->nullable();
            $table->string('have_ca')->default('0');
            $table->string('have_mfile')->default('0');
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
        Schema::dropIfExists('log_mfile');

    }
}
