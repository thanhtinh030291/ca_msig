<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClaimTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('claim', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code_claim');
            $table->string('code_claim_show')->nullable();
            $table->string('barcode')->nullable();            
            $table->string('url_file')->nullable();
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
        Schema::dropIfExists('claim');
    }
}
