<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemOfClaimTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_of_claim', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('content')->nullable();
            $table->string('amount')->nullable();
            $table->integer('status')->default('1');
            $table->integer('claim_id')->nullable();
            $table->integer('reason_reject_id')->nullable();
            $table->longText('parameters')->nullable();
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
        Schema::dropIfExists('item_of_claim');
    }
}
