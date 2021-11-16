<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHospitalRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hospital_request', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('claim_id');
            $table->bigInteger('prov_gop_pres_amt')->default('0');
            $table->text('url_form_request')->nullable();
            $table->text('url_corner_profile')->nullable();
            $table->text('url_attach_email')->nullable();
            $table->integer('type_gop')->default('0');
            $table->text('note')->nullable();
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
        Schema::dropIfExists('hospital_request');
    }
}
