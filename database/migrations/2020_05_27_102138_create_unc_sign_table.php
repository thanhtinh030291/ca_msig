<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUncSignTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('unc_sign', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('url_non_sign')->nullable();
            $table->string('url_signed')->nullable();
            $table->integer('group_unc_id');
            $table->integer('status')->default('0');
            $table->dateTime('sign_at')->nullable();
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
        Schema::dropIfExists('unc_sign');
    }
}
