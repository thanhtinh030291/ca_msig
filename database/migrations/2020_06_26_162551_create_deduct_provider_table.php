<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeductProviderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deduct_provider', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('provider_id');
            $table->float('amt',11,0);
            $table->string('claim_no');
            $table->string('comment')->nullable();
            $table->integer('type')->comment('0 : add deduct ; 1: div deduct');
            $table->integer('is_deleted')->default('0');
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
        Schema::dropIfExists('deduct_provider');
    }
}
